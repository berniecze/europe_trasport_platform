#! /bin/bash

# setup php.ini
if [ ! -f docker/config/php.ini ]; then
  cp docker/config/php.ini.template docker/config/php.ini
fi

# setup docker-compose.yml
if [ ! -f docker-compose.yml ]; then
	cp docker-compose.yml.template docker-compose.yml
fi

# os detection
os=${OSTYPE//[0-9.-]*/}

case "$os" in
	darwin)
		# mac
	 	echo "xdebug.remote_host = docker.for.mac.localhost" >> config/php.ini
	 	echo "volumes:" >> docker-compose.yml
		echo "    mysql:" >> docker-compose.yml
		echo "    project:" >> docker-compose.yml
		;;

	msys)
		# win
		echo "COMPOSE_CONVERT_WINDOWS_PATHS=1" > .env
		echo "volumes:" >> docker-compose.yml
		echo "    mysql:" >> docker-compose.yml
		echo "    project:" >> docker-compose.yml

		echo "changing: ./data/db:/var/lib/mysql to mysql:/var/lib/mysql"
		sed -i 's/\.\/data\/db\:\/var\/lib\/mysql/\mysql\:\/var\/lib\/mysql/g' docker-compose.yml

		echo ""

		read -rp "Continue? (y/n)" -n 1 -r
		echo ""
		if [[ ! $REPLY =~ ^[Yy]$ ]]; then
			 exit 1
		fi
		;;

	linux)
		# linux
		echo "xdebug.remote_host = 172.17.0.1" >> config/php.ini
		;;
	 *)
		echo "neznámý systém, nelze pokračovat"
		exit 1
esac

# build container
docker-compose up -d

# install dependencies
docker-compose exec php su --command="composer install --prefer-dist --no-interaction" www-data

# create database
dbExists=$(docker-compose exec db mysql --skip-column-names -e "SHOW DATABASES LIKE 'project'")
if [ -z "$dbExists" ]; then
	docker-compose exec db mysql -e "CREATE DATABASE project CHARACTER SET utf8 COLLATE utf8_unicode_ci"
fi

docker-compose run --rm --user=node tools bower install

# run migrations
docker-compose exec php su --command="php bin/console migrations:reset" www-data

echo "Installation successful"
