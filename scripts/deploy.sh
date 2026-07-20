#!/bin/bash

set -e

echo "========== DEPLOY BAŞLADI =========="

cd /home/ubuntu/php-api

# Eski çalışan image tag'ini al
OLD_TAG=$(docker inspect --format='{{.Config.Image}}' php_api_prod | cut -d':' -f2)

echo "Eski Tag : $OLD_TAG"
echo "Yeni Tag : $IMAGE_TAG"

# Yeni image indir
docker compose -f docker-compose.prod.yml pull web

# Yeni image deploy et
docker compose -f docker-compose.prod.yml up -d --force-recreate web

# Health kontrolü
for i in {1..30}
do
    STATUS=$(docker inspect --format='{{.State.Health.Status}}' php_api_prod)

    echo "Deneme $i -> $STATUS"

    if [ "$STATUS" = "healthy" ]; then
        echo "✅ Deployment başarılı."

        docker image prune -f

        exit 0
    fi

    sleep 2
done

echo "❌ Deployment başarısız."

echo "Rollback başlatılıyor..."

export IMAGE_TAG=$OLD_TAG

docker compose -f docker-compose.prod.yml up -d --force-recreate web

docker image prune -f

exit 1
