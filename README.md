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

### 6. Listen event add/update product to send email

---
**_NOTE:_**
The function sends email while listening event adds or updates a product was process by an async messenger.
That means even you setting up the wrong email server in env. The main features still work properly.
---

Messenger log: 
```html
var/log/email_consumer.out.log
```

## Requirement risk:
Should be clear before implementation: 
1. categoriesEId in file products.json referent to category id or eid? It will make sense if it is eid, but in that case, eid should be not null.
2. Which key accepted for categories? categoriesEId or categoryEId? 
