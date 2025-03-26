# Symfony Performance Test Project

This project demonstrates different database access methods in Symfony with performance testing using k6.

## Features

- Symfony 6.4 with PHP 8.2
- PostgreSQL database access via PDO and Doctrine
- Redis integration
- Nginx + PHP-FPM setup
- Docker Compose configuration
- k6 performance testing
- Makefile for common commands

## Prerequisites

- Docker and Docker Compose
- k6 (for performance testing)

## Setup

1. Clone the repository
2. Copy `.env.example` to `.env` and adjust values if needed
3. Build and start the containers:
```bash
make build
make up
```

4. Run database migrations:
```bash
make migrate
```

## Available APIs

- PDO Test: `http://localhost:8080/api/pdo/test`
- Doctrine Test: `http://localhost:8080/api/doctrine/test`
- Redis Test: `http://localhost:8080/api/redis/test`

## Performance Testing

Run k6 tests using the following commands:

```bash
# Test PDO endpoint
make test-pdo

# Test Doctrine endpoint
make test-doctrine

# Test Redis endpoint
make test-redis
```

## Available Make Commands

- `make up` - Start containers
- `make down` - Stop containers
- `make build` - Build containers
- `make migrate` - Run database migrations
- `make create-migration` - Create new migration
- `make clean` - Clean up containers and volumes
- `make logs` - View container logs

## Performance Optimizations

- PHP-FPM configuration optimized for high concurrency
- Nginx configuration with gzip compression and caching
- OPcache enabled and configured
- Composer autoloader optimization
- Redis for caching 