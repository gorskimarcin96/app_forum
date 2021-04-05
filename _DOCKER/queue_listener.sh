#!/bin/bash
date > /var/www/html/_DOCKER/logs/queue_listener/queue.log
php bin/console c:c >> /var/www/html/_DOCKER/logs/queue_listener/queue.log
php bin/console messenger:consume >> /var/www/html/_DOCKER/logs/queue_listener/queue.log
