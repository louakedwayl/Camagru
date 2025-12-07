all: 
	docker compose up

clean:
	- docker stop $(shell docker ps -q)
	- docker rmi $(shell docker images -aq)

