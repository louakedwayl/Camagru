# Nom de l'image et du conteneur
IMAGE_NAME=camagru-image
CONTAINER_NAME=camagru-container
PORT=8080

# Construire l'image Docker
build:
	docker build -t $(IMAGE_NAME) .

# Lancer le conteneur (en mode détaché avec volume pour dev)
run:
	docker run -d -p $(PORT):80 --name $(CONTAINER_NAME) -v $(PWD):/var/www/html $(IMAGE_NAME)

# Arrêter le conteneur
stop:
	docker stop $(CONTAINER_NAME) || true
	docker rm $(CONTAINER_NAME) || true

# Reconstruire l'image et relancer le conteneur
rebuild: stop build run

# Afficher les logs du conteneur
logs:
	docker logs -f $(CONTAINER_NAME)

# Supprimer l'image Docker
clean: stop
	docker rmi $(IMAGE_NAME)

