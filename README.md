# CTF Platform - Custom MVC

## Requirements
PHP >= 8.1\
Apache2\
Docker

## Building
Build run:
```sh
docker-compose up --build -d
```
Running:
```sh
docker-compose up
```

## First run
After building via docker-compose, go to localhost:8080/install.php and fill form.

### Database

```
Database address: db
Database: ctfcm
Database user: ctfcm
Database password: 12###magicPassword###
```
After installation remove public/install.php

### Pointing system
```php
($points > 0 && $points <= 30): $level = 'easy';
($points > 30 && $points <= 60): $level = 'medium';
($points > 60 && $points <= 100): $level = 'hard';
```

## Permissions

| INT | description 
| --- | -----------
| 0 | Forbiden to acces site
| 1 | Standard user
| 2 | admin account
| 3 | for CTF purpose

## Developer notes
When adding tasks, they are saved into src/tasks.json.
Admin users are hardcoded in public/install.php ;)