.PHONY: up down build test clean build-base build-app clean-images

# Default target
all: build up

# Build base image with PHP and extensions
build-base:
	docker build -t php-crew-php-base:latest -f docker/php/Dockerfile.base .

# Build application image (depends on base image)
build-app: build-base
	export COMPOSE_DOCKER_CLI_BUILD=1
	export DOCKER_BUILDKIT=1
	docker compose build

# Build all images
build: build-app

# Start containers
up: build
	docker compose up -d

# Stop containers
down:
	docker compose down

# Clean up containers and volumes
clean:
	docker compose down -v
	rm -rf var/cache/* var/log/*

# Clean up images
clean-images:
	docker rmi php-crew-php-base:latest || true
	docker compose down --rmi all

# Full clean (containers, volumes, and images)
clean-all: clean clean-images

# Performance tests
test-pdo:
	k6 run tests/k6/pdo-test.js

test-doctrine:
	k6 run tests/k6/doctrine-test.js

test-redis:
	k6 run tests/k6/redis-test.js

test-gc:
	k6 run tests/k6/gc-example-test.js

test-gc-without:
	k6 run tests/k6/gc-example-test.js --scenario without_gc

test-gc-with:
	k6 run tests/k6/gc-example-test.js --scenario with_gc

# Database commands
migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate

create-migration:
	docker compose exec php php bin/console doctrine:migrations:generate

# Logs
logs:
	docker compose logs -f

# PHP CLI commands
php-cli:
	docker compose exec php-cli sh

symfony-console:
	docker compose exec php-cli php bin/console

# Rebuild everything from scratch
rebuild: clean-all build up
