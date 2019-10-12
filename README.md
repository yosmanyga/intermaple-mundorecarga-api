# Install

## Dev

docker network create mundorecarga

# Run

## Dev

export UID
export GID
docker-compose \
-f docker/common.yml \
-f docker/dev.yml \
-p mundorecarga_api \
up -d --remove-orphans --force-recreate

# Prod

docker network create mundorecarga

cd proxy
docker-compose -f docker/yml -p proxy up -d
cd ..

cd mundorecarga-api

export UID
export GID
docker-compose \
-f docker/common.yml \
-f docker/prod.yml \
-p mundorecarga_api \
up -d --remove-orphans --force-recreate

# Install

composer install

cp config/parameters.dist.yml config/parameters.yml

nano config/parameters.yml

chmod 777 -R var/*

docker exec -it mundorecarga_api_php sh

php bin/app.php /load-countries
php bin/app.php /load-providers
php bin/app.php /load-promotions


