FROM golang:1.24.4-alpine3.22 AS builder

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download

COPY . .

RUN go build -o app main.go

FROM alpine:latest
WORKDIR /app

COPY --from=builder /app/app .

EXPOSE 8000

CMD ["./app"]
