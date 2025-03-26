.PHONY: up down build test clean

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

test-pdo:
	k6 run tests/k6/pdo-test.js

test-doctrine:
	k6 run tests/k6/doctrine-test.js

test-redis:
	k6 run tests/k6/redis-test.js

migrate:
	docker-compose exec php php bin/console doctrine:migrations:migrate

create-migration:
	docker-compose exec php php bin/console doctrine:migrations:generate

clean:
	docker-compose down -v
	rm -rf var/cache/* var/log/*

logs:
	docker-compose logs -f 