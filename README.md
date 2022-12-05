url of the project mounted: https://backbones.store/zipConsult/(zip_code)

# zip_consult
Project to solve the backbones system selection process.

Project developed in laravel 9 using PHP 8.1.

This project uses a mysql database.

Steps to execute the system.

After to clone the project and have the packages and libraries updated.
-First one verify to your .env config have the following queue parameter with databases:

QUEUE_CONNECTION=database

-Second run the custom command  : php artisan create:jobs in order to make the jobs wiches going to migrate the information of the xls files to sql

-Third run the command: php artisan queue:listen  to run the jobs this proccess could be late so be patient.

Configuration of the project in Ubuntu 20.04 where the project was mounted fot the test: 

Installing apache:
sudo apt update
sudo apt install apache2

Installing mysql:
sudo apt install mysql-server
sudo mysql
mysql create database zip_code;

Installing PHP :
sudo apt install php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath


 
