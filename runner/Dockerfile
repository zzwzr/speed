FROM debian:bookworm-slim
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl gnupg lsb-release apt-transport-https wget git unzip \
    docker.io \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /etc/apt/trusted.gpg.d/php.gpg \
    && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && apt-get install -y --no-install-recommends \
    php8.2-cli php8.2-mbstring php8.2-xml php8.2-redis \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -L https://dl.gitea.com/act_runner/0.2.13/act_runner-0.2.13-linux-amd64 -o /usr/local/bin/act_runner \
    && chmod +x /usr/local/bin/act_runner

WORKDIR /data
ENTRYPOINT ["act_runner", "daemon"]
