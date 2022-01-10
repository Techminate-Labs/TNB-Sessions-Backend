# TNB Sessions, Eveent Manager

TNB-Sessions is an event management web application that integrates with the TNB blockchain and community.

[More information about TNB / The New Boston](https://thenewboston.com/)

Framework: Laravel
Packages: Sanctum

## Setup instructions 

1. Install xampp
2. then go to `xampp\htdocs`
3. fork this repository
4. clone the forked repository in the `xampp\htdocs` folder
5. launch xampp, start apache and mysql modules and go to `localhost/phpmyadmin`
5. create a database and add it to the database in env.example
6. copy env.example and name it .env
7. open terminal and run `composer install`
8. then run `php artisan storage:link`
9. then run `php artisan serve`
