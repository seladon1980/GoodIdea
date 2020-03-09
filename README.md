Docker environment for a Symfony4 project
==================================

# How to run
* docker-compose up -d
* docker-compose exec php-fpm bash
* composer install
* php bin/console doctrine:migrations:migrate
* http://localhost:40002/blog/

# load fixture (don't use)
* php bin/console doctrine:fixtures:load