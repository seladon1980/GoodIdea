Docker environment for a Symfony4 project
==================================

# How to run
* docker-compose exec php-fpm bash
* composer install
* php bin/console doctrine:migrations:migrate
//* bin/console doctrine:schema:update --force
* php bin/console cache:clear

# load fixture
* php bin/console doctrine:fixtures:load