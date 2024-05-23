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

bash-web:
	docker compose exec web /bin/sh;

bash-users:
	docker compose exec users /bin/sh;

reset-web:
	docker compose exec web ./rr reset;

reset-users:
	docker compose exec users ./rr reset;

reset: reset-web reset-users
