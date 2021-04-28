docker exec -it forum-app php c:c
docker exec -it forum-app php bin/console doctrine:mongodb:schema:drop --env=test
docker exec -it forum-app php bin/console doctrine:mongodb:schema:create --env=test
docker exec -it forum-app php bin/phpunit
