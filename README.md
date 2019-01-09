# Install

## Dev

docker network create frontend

docker network create backend

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

docker network create frontend

docker network create backend

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

# Test

php bin/app.php /populate
php bin/app.php /add-user +17025173777
php bin/app.php /add-role US 1 7025173777 admin
php bin/app.php /add-role US 1 7025173777 operator
php bin/app.php /add-role EC 593 984994087 operator
php bin/app.php /add-role US 1 7866237191 admin
php bin/app.php /add-role US 1 7866237191 operator

## Reseller

php bin/app.php /add-role US 1 7025173777 reseller
php bin/app.php /reseller/register-user US 1 7025173777 Yosmy
php bin/app.php /reseller/user/add-provider 5b4ebb26053ff CUCU 2.5
php bin/app.php /reseller/user/add-provider 5b4ebb26053ff CBCU 2.5
php bin/app.php /reseller/user/add-provider 5b4ebb26053ff CACU 2.5
php bin/app.php /reseller/user/add-provider 5b4ebb26053ff NUCU 2.5
