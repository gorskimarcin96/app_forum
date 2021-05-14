#stop default services
service mysql stop
service apache2 stop

docker rm $(docker ps -a -q) -f #removes your containers
docker network prune -f #remove all unused networks
#docker-compose build --no-cache #uncomment if you want to reinstall containers
docker-compose up -d #running your containers
iptables -A INPUT -p tcp -d 0/0 -s 0/0 --dport 9003 -j ACCEPT #fixing ports for your containers
chmod 777 -R ../*
rm -Rf ../var/*
docker exec -it forum-app composer update
docker exec -it forum-app php bin/console c:c
docker exec -it forum-app php bin/console doctrine:schema:update
docker exec -it forum-app php bin/console doctrine:mongodb:schema:update
#sleep 5
#docker exec -it forum-app php bin/console fos:elastica:create
#docker exec -it forum-app php -d memory_limit=-1 bin/console fos:elastica:populate
