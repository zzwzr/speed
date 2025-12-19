package main

import (
	"context"
	"flag"
	"fmt"
	"log"
	"net"
	"net/http"
	"net/http/httputil"
	"net/url"
	"strings"
	"sync"
	"sync/atomic"
	"time"
)

const (
	Attempts int = iota
	Retry
)

// 保存一个后端服务器的信息
type Backend struct {
	URL          *url.URL
	Alive        bool
	mux          sync.RWMutex
	ReverseProxy *httputil.ReverseProxy
}

// ServerPool 保存所有后端列表
type ServerPool struct {
	backends []*Backend
	current  uint64
}

// 设置后端存活状态（写锁）
func (b *Backend) SetAlive(alive bool) {
	b.mux.Lock()
	b.Alive = alive
	b.mux.Unlock()
}

// 获取后端存活状态（读锁）
func (b *Backend) IsAlive() (alive bool) {
	b.mux.RLock()
	alive = b.Alive
	b.mux.RUnlock()
	return
}

func (s *ServerPool) AddBackend(backend *Backend) {
	s.backends = append(s.backends, backend)
}

func (s *ServerPool) NextIndex() int {
	return int(atomic.AddUint64(&s.current, uint64(1)) % uint64(len(s.backends)))
}

// MarkBackendStatus 更新后端状态
func (s *ServerPool) MarkBackendStatus(backendUrl *url.URL, alive bool) {
	for _, b := range s.backends {
		if b.URL.String() == backendUrl.String() {
			b.SetAlive(alive)
			break
		}
	}
}

// 返回下一个可用后端
func (s *ServerPool) GetNextPeer() *Backend {

	next := s.NextIndex()

	l := len(s.backends) + next
	for i := next; i < l; i++ {
		idx := i % len(s.backends)

		if s.backends[idx].IsAlive() {
			if i != next {
				atomic.StoreUint64(&s.current, uint64(idx))
			}
			return s.backends[idx]
		}
	}
	return nil
}

// 更新后端状态
func (s *ServerPool) HealthCheck() {
	for _, b := range s.backends {
		status := "up"
		alive := isBackendAlive(b.URL)
		b.SetAlive(alive)
		if !alive {
			status = "down"
		}
		log.Printf("%s [%s]\n", b.URL, status)
	}
}

// 通过建立TCP连接来检查后端是否处于活动状态
func isBackendAlive(u *url.URL) bool {
	timeout := 2 * time.Second
	conn, err := net.DialTimeout("tcp", u.Host, timeout)
	if err != nil {
		log.Println("err:", err)
		return false
	}
	defer conn.Close()
	return true
}

// 返回请求的尝试次数
func GetAttemptsFromContext(r *http.Request) int {
	// 断言就是我确定这个值就是指定类型
	// 如果用 .(int) 断言，结果获取的值不是 int 就会立即 pani
	if attempts, ok := r.Context().Value(Attempts).(int); ok {
		return attempts
	}
	return 1
}

// 返回请求的重试次数
func GetRetryFromContext(r *http.Request) int {
	if retry, ok := r.Context().Value(Retry).(int); ok {
		return retry
	}
	return 0
}

// lb负载平衡传入请求
func lb(w http.ResponseWriter, r *http.Request) {
	attempts := GetAttemptsFromContext(r)
	if attempts > 3 {
		log.Printf("%s(%s) Max attempts reached, terminating\n", r.RemoteAddr, r.URL.Path)
		http.Error(w, "Service not available", http.StatusServiceUnavailable)
		return
	}

	peer := serverPool.GetNextPeer()
	if peer != nil {
		peer.ReverseProxy.ServeHTTP(w, r)
		return
	}
	http.Error(w, "Service not available", http.StatusServiceUnavailable)
}

// 每2分钟运行一次后端状态检查例程
func healthCheck() {
	t := time.NewTicker(time.Minute * 2) // 1. 创建一个定时器，每 2 分钟触发一次
	for {                                // 2. 启动一个无限循环，持续执行
		select { // 3. 等待事件
		case <-t.C: // 4. 每当定时器触发（从 t.C 读到值）
			log.Println("Starting health check...")
			serverPool.HealthCheck() // 5. 调用 serverPool.HealthCheck()，对所有后端依次检查存活状态
			log.Println("Health check completed")
		}
	}
}

var serverPool ServerPool

func main() {
	var serverList string
	var port int

	flag.StringVar(&serverList, "backends", "", "负载均衡后端地址")
	flag.IntVar(&port, "port", 3030, "服务器端口")
	flag.Parse()

	if len(serverList) == 0 {
		log.Fatal("请提供一个或多个后端以实现负载平衡")
	}
	// 解析服务器
	tokens := strings.Split(serverList, ",")
	for _, tok := range tokens {

		// 将url地址解析成*URL结构体
		serverUrl, err := url.Parse(tok)
		if err != nil {
			log.Fatal(serverUrl)
		}

		proxy := httputil.NewSingleHostReverseProxy(serverUrl)
		proxy.ErrorHandler = func(writer http.ResponseWriter, request *http.Request, e error) {
			log.Printf("[%s] %s \n", serverUrl.Host, e.Error())
			retries := GetRetryFromContext(request)
			if retries < 3 {
				select {
				case <-time.After(10 * time.Millisecond):
					ctx := context.WithValue(request.Context(), Retry, retries+1)
					proxy.ServeHTTP(writer, request.WithContext(ctx))
				}
				return
			}
			serverPool.MarkBackendStatus(serverUrl, false)

			// 如果对不同后端的几次尝试使用相同的请求路由，增加计数
			attempts := GetAttemptsFromContext(request)
			log.Printf("%s(%s) Attempting retry %d\n", request.RemoteAddr, request.URL.Path, attempts)

			ctx := context.WithValue(request.Context(), Attempts, attempts+1)
			lb(writer, request.WithContext(ctx))
		}

		serverPool.AddBackend(&Backend{
			URL:          serverUrl,
			Alive:        true,
			ReverseProxy: proxy,
		})
	}
	server := http.Server{
		Addr:    fmt.Sprintf(":%d", port),
		Handler: http.HandlerFunc(lb),
	}

	go healthCheck()

	log.Printf("负载均衡开始启动 : %d\n", port)

	if err := server.ListenAndServe(); err != nil {
		log.Fatal(err)
	}
}
