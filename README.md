# GofingoTest

## 1. Setup environment

### 2. Requirement
- git
- docker
- docker-composer

### 3. Source clone
git clone https://github.com/santndev/GofingoTest.git

### 4. Create environment
cd [local source place]
Run commands:
```html
docker-compose -f .docker/docker-compose.yml build
docker-compose -f .docker/docker-compose.yml up -d
docker exec -it gofingo-test-apache //bin//bash
composer install -n
bin/console doctrine:migrations:diff --no-interaction
bin/console doctrine:migrations:migrate --no-interaction
```

### 5. Run command
```html
bin/console app:import
```
