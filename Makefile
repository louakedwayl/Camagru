all: 
	docker compose up

clean:
	docker compose down --rmi all -v
