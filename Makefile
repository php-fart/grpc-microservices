build:
	docker compose up --no-start;

start:
	docker compose up --remove-orphans -d;

up: build start

stop:
	docker compose stop;

down:
	docker compose down;

restart:
	docker compose restart;

list:
	docker compose ps;

log-tail:
	docker compose logs --tail=50 -f;

# =========================

install:
	for service in web users; do \
		cd $$service; \
		composer install; \
		cd ..; \
	done


reinstall-grpc-shared:
	for service in web users; do \
		cd $$service; \
		rm -rf vendor/ms; \
		composer require ms/grpc-shared; \
		cd ..; \
	done

clear-cache:
	for service in web users; do \
		cd $$service; \
		rm -rf runtime/cache; \
		cd ..; \
	done

compile-proto:
	chmod +x lib/grpc-shared/bin/console
	chmod +x lib/grpc-shared/bin/protoc-gen-php-grpc
	php lib/grpc-shared/bin/console generate;

update-proto: compile-proto reinstall-grpc-shared;

composer-du:
	for service in web users; do \
		cd $$service; \
		composer du; \
		cd ..; \
	done

# =========================

bash-web:
	docker compose exec web /bin/sh;

bash-users:
	docker compose exec users /bin/sh;

reset-web:
	docker compose exec web ./rr reset;

reset-users:
	docker compose exec users ./rr reset;

reset: reset-web reset-users
