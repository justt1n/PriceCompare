## About Project
- PHP 8.2
- Laravel 10
- Sail 1.26
- Nodejs 20
- MySQL 8.0.32
- Tailwindcss 3.2.4
- DaisyUI 4.4.11
- Guzzlehttp 7.2
- Breeze 1.26
- jQuery 3.6.4
## Get Started
#### Clone this Project 

### * Ubuntu:
#### Install composer (optional):

```bash
sudo apt update
sudo apt install php-cli unzip -y
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```
#### Setup env and docker (make sure you have install php composer or following the optional step above):
``` bash
cd PriceCompare
git checkout develop
composer require laravel/sail --dev 
```
- Following install step.
- After installed, test the sail using:
```bash
./vendor/bin/sail -v
```
It should be
```
docker-compose version X.X.X, build ....
```
- Change .env.example to .env
```bash
touch .env
cat .env.example >> .env
```

### * Windows:
#### Install composer (optional):
- Following this tutorial from Geeksforgeeks: <a href="https://www.geeksforgeeks.org/how-to-install-php-composer-on-windows/"> Click here </a>
#### Setup env and docker (make sure you have install php composer or following the optional step above):
- Open powershell (not cmd):
```bash
cd PriceCompare
git checkout develop
composer require laravel/sail --dev 
```
- Following install step.
- After installed, test the sail using:
```bash
./vendor/bin/sail -v
```
It should be
```
docker-compose version X.X.X, build ....
```
- Change .env.example to .env
```bash
touch .env
cat .env.example >> .env
```

#### Start the project:
```bash
./vendor/bin/sail up --build -d
```
You can remove the _--build_ flag in the next time you start (Only when changed anything in the docker then you need this flag). <br />
#### Get the packages:
```bash
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan optimize
```
- When install completed:
```bash
./vendor/bin/sail npm run dev
```
The website is up: http://localhost:80
#### Create sample database (Only in the first time or wanna reset database):
```bash
./vendor/bin/sail artisan migrate:refresh --seed
```
### Crawl data 
Using this command with **_site_** argument is name of site: 
```bash
./vendor/bin/sail artisan scrape:<site>
```
Sites supported:
| site | argument | status |
| ------ | ------ |------|
|  Tiki  | tiki   | working
|  The Gioi Di Dong | tgdd       | working|
|  FPT Shop | fpt| working|
|  Dien Thoai Gia Kho | dtgk | working |
| Phong Vu | pv |working|
| Di Dong Viet | ddv | working |

example: You wanna crawl tgdd then:
```bash
./vendor/bin/sail artisan scrape:tgdd
```
### Happy coding!

