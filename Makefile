NAME = camagru

all: up

up:
	docker compose up --build -d

down:
	docker compose down

stop:
	docker compose stop

start:
	docker compose start

restart: down up

logs:
	docker compose logs -f

clean: down
	docker compose down --rmi all -v

fclean: clean
	docker system prune -af --volumes

re: clean up

.PHONY: all up down stop start restart logs clean fclean re
