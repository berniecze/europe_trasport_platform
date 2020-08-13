Docker for project application
=================
Small WIP platform for ordering shuttle transport across countries, administrating and supervising your business

Runs in Docker environment using PHP 7.1, Nette framework
It started as a typical MVC structure but it's currently evolving to the Domain Driven Design

Project structure
-------------
Project is located in the `/src` directory
As said it started as an MVC/MVP structured project, so there is `AdminModule` and `FrontModule` with their components and Presenters

Currently, it's in the middle of rewriting to the domain design so you can find
* `Application` for `*UseCase` and `*Request` VO classes. 
* `Domain` with the Entities and their attributes
* `Infrastructe` with custom Exceptions,  Doctrine's Repository and Query Builder classes and `Entity` directory with .xml mapping for mapping Domain's entities to the database model and Doctrine's ORM


ToDo
-------------
* implement against interfaces
* add tests for business workflow 

Prerequisites
-------------
* `Docker Community edition` from https://store.docker.com/search?type=edition&offering=community
  * Version for Mac: https://store.docker.com/editions/community/docker-ce-desktop-mac
  * Version for Win: https://store.docker.com/editions/community/docker-ce-desktop-windows

Instalation
-----------
* add `127.0.0.1    project.l` to your /etc/hosts file
* clone this repo: `git clone git@gitlab.mallgroup.com:kosikcz/project-docker.git project`
* go to project root directory ` cd project` 
* run `./install.sh`

Start / stop
-----------
* use `docker-compose start/stop`

urls, passwords, parameters
----------------
* local config.neon file is placed in the root /config folder

#### mysql
* www url: http://project.l
* db user/passs: root/toor

Useful commands
----------------
All commands must be executed inside php container under www-data user. Two options exists:
1. Run interactive bash console and then execute php commands directly: `docker exec -i -t europe_transport_platform_php_1 su -s /bin/bash www-data`
2. Run commands directly from host: docker exec europe_transport_platform_php_1 su --command="<command>" www-data

### Some most common commands
* `composer install` install PHP dependencies
* `php bin/console` list all application commands
* `php bin/console migrations:contiue` execute db migrations
* `php bin/console orm:info` & `php bin/console orm:validate-schema` for validating entity mapping
* `php bin/console orm:schema-tool:update --dump-sql` generates sql for changes between mapping (entities) and db schema
* `composer lint` run linter - checking PHP files syntax
* `composer cs` check coding style
* `composer run phpcsf` fix coding style errors (not all can be fixed automatically)
* `composer phpstan` run PHPStan - static analysis tool
* `composer tests` run PHPUnit

### Run bash in container
Run bash under www-data user for running commands, composer, ...
```sh
docker exec -i -t europe_transport_platform_php_1 su -s /bin/bash www-data
```
Run bash under root (container name can be changed)
```sh
docker exec -i -t europe_transport_platform_php_1 /bin/bash
```

Uninstalation
-----------------
* `docker-compose down`
* remove project root directory
