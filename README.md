# Symfony 5.3 CURD product and import from csv file. Dockernizer, code convention, test...

## 1. Setup environment

### 2. Requirement
- git
- docker
- docker-composer

### 3. Source clone
git clone https://github.com/santndev/Symfony-5.3-simple.git

### 4. Create environment
cd [local source place]
Run commands:
```html
docker-compose -f .docker/docker-compose.yml build
docker-compose -f .docker/docker-compose.yml up -d
docker exec -it gofingo-test-apache //bin//bash
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
