FROM phpswoole/swoole:php8.4-alpine

# 复制代码
WORKDIR /app
COPY server.php .

# 容器启动即运行 swoole 服务器
CMD ["php", "server.php"]
