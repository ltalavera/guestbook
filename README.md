# GuestBook #

## Requirements ##

* Git
* Apache 2.x
* PHP 5.6.x
* Mysql >= 5.6.x
* Laravel Framework
* Composer

## Init project ##
* Execute: cd backend
* Execute: composer install
* Set access to some directories with the following commands:
* * sudo chmod -R 777 bootstrap
* * sudo chmod -R 777 storage
* Configure .env file with your local data if it's necessary
* Create DB "GuestBook"
* Executing: php artisan migrate:refresh
* Create roles and a User executing: php artisan db:seed
* Execute: php artisan serve
* Open browser and go to http://localhost:8000/api/guests
