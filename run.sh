chmod 777 -R var
rm -R var/cache/*
php bin/console c:c
chmod 777 -R var
yarn encore dev