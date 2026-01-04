all: 
	docker compose up --build

clean:
	docker compose down --rmi all -v
