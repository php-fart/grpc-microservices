FROM ghcr.io/roadrunner-server/roadrunner:2023.3.12 as rr

FROM ghcr.io/spiral/php-grpc:8.2 as backend

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

ARG SERVICE_NAME
ARG APP_VERSION=v1.0
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV SERVICE_NAME=php-$SERVICE_NAME

COPY --from=rr /usr/bin/rr /bin
COPY ./proto /proto
COPY ./lib /lib

WORKDIR /app

#RUN composer config --no-plugins allow-plugins.spiral/composer-publish-plugin false
#RUN composer install --no-dev --no-interaction --no-progress --no-suggest --optimize-autoloader

CMD ["/bin/rr", "serve", "-e", "-c", "/app/.rr-prod.yaml"]
