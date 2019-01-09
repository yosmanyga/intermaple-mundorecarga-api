#!/bin/bash

cd /home/yosmanyga/Work/Projects/intermaple/mundorecarga-api/code

docker-compose \
-f docker/common.yml \
-f docker/dev.yml \
-p mundorecarga_api \
stop