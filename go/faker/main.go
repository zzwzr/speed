package main

import (
	"fmt"
	"log"
	"math/rand"
	"net/http"
	"reflect"
	"strconv"
	"time"

	"github.com/bxcodec/faker/v4"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

type Order struct {
	ID        uint      `gorm:"primaryKey" faker:"-"`
	OrderNo   string    `gorm:"type:varchar(255);unique;not null" faker:"uuid_digit"`
	UserId    uint      `gorm:"not null" faker:"boundary_start=1, boundary_end=1000"`
	Amount    float64   `gorm:"type:decimal(10,2);not null" faker:"boundary_start=1, boundary_end=1000"`
	Status    int8      `gorm:"type:tinyint;default:2;comment:订单状态(0未支付,1已支付,2已发货)" faker:"boundary_start=0, boundary_end=2"`
	Address   string    `gorm:"type:varchar(255);not null" faker:"chinese"`
	CreatedAt time.Time `gorm:"type:datetime(0)"`
}

var db *gorm.DB

func main() {
	faker.AddProvider("chinese", func(v reflect.Value) (interface{}, error) {
		return randomChinese(15), nil
	})

	dsn := "root:123123@tcp(mysql:3306)/concurrency?charset=utf8mb4&parseTime=True&loc=Local"
	var err error
	db, err = gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatal("数据库建立连接失败:", err)
	}
	// 创建表
	if err := db.AutoMigrate(&Order{}); err != nil {
		log.Fatal("创建表失败:", err)
	}

	http.HandleFunc("/", insertOrders)
	fmt.Println("服务启动成功!")
	if err := http.ListenAndServe(":8000", nil); err != nil {
		log.Fatal("HTTP 服务器已断开:", err)
	}
}

func insertOrders(w http.ResponseWriter, r *http.Request) {
	countStr := r.URL.Query().Get("count")
	count := 100
	if countStr != "" {
		if c, err := strconv.Atoi(countStr); err == nil && c > 0 {
			count = c
		}
	}
	var orders []Order

	for i := 0; i < count; i++ {
		var o Order
		if err := faker.FakeData(&o); err != nil {
			log.Println("生成数据失败：", err)
		}
		o.CreatedAt = randomTime()
		orders = append(orders, o)
	}

	if err := db.Create(orders).Error; err != nil {
		log.Println("数据层创建失败：", err)
	}
	fmt.Println("数据创建成功!")
}

func randomTime() time.Time {
	start := time.Date(2018, 1, 1, 0, 0, 0, 0, time.Local)
	end := time.Now()
	delta := end.Sub(start)
	randSec := rand.Int63n(int64(delta.Seconds()))
	return start.Add(time.Duration(randSec) * time.Second)
}

var commonSimplified = []rune("的一是在不了有和人这中大为上个国我以要他时来用们生到作地于出就分对成会可主发年动同工也能下过子说产种面而方后多定行学法所民得经十三之进等部度家电力里如水化高自二理起小物现实加量都两体制机当使点从业本去把性好应开它合还因由其些然前外天政四日那社义事平形相全表间样与关各重新线内数正心反你明看原又么利比或但质气第向道命此变条只没结解问意建月公无系军很情者最立代想已通并提直题党程展五果料象员革位入常文总次品式活设及管特件长求老头基资边流路级少图山统接知较将组见计别她手角期根论运农指几九区强放决西被干做必战先回则任取据处队南给色光门即保治北造百规热领七海口东导器压志世金增争济阶油思术极交受联什认六共权收证改清己美再采转更单风切打白教速花带安场身车例真务具万每目至达走积示议声报斗完类八离华名确才科张信马节话米整空元况今集温传土许步群广石记需段研界拉林律叫且究观越织装影算低持音众书布复容儿须际商非验连断深难近矿千周委素技备半办青省列习响约支般史感劳便团往酸历市克何除消构府太准精值号率族维划选标写存候毛亲快效斯院查江型眼王按格养易置派层片始却专状育厂京识适属圆包火住调满县局照参红细引听该铁价严龙")

func randomChinese(n int) string {
	runes := make([]rune, n)
	for i := 0; i < n; i++ {
		runes[i] = commonSimplified[rand.Intn(len(commonSimplified))]
	}
	return string(runes)
}
