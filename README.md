<p align="center"><img src="public/img/logo.png" width="400"></p>

## About TaraPay 
Simple project on Laravel framework for visual test API's payment service of TaraPay.

## Requirments

1. PHP >= 7.3
2. MySQL >= 5.7


## Deploy project

1. git clone git@github.com:amirajlo/TaraPay.git
2. cd TaraPay
3. composer install
4. cp .env.example .env
5. edit  .env with Your Username,Password,Gateway
6. php artisan migrate:fresh
7.  php artisan key:generate
8. php artisan serve
9. echo '127.0.0.1:8000' 


