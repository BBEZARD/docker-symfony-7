#!/bin/bash

# Mets à jour les conteneurs Docker du projet
docker compose --env-file "docker/config/.env" -f docker/docker-compose.yaml build
docker compose --env-file "docker/config/.env" -f docker/docker-compose.yaml up -d