#!/bin/bash

restart() {
    export UID
    
    export GID

    docker-compose -f docker/common.yml -f docker/dev.yml -p mundorecarga_api up -d --remove-orphans --force-recreate
}

cd /home/yosmanyga/Work/Projects/intermaple/mundorecarga-api/code

while true
do
    restart

    read -p "Reset?" answer
done
