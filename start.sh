#!/bin/bash

docker-compose pull
docker-compose down -v --remove-orphans
docker-compose up -d --build

docker-compose logs -f app-setup
