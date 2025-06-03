#!/bin/bash

#Update package list atau repository
apt update && apt upgrade -y

#Install Git dan Curl
apt install -y git curl

#Install PHP sama ekstensinya
apt install -y php php-curl php-fpm php-bcmath php-gd php-soap php-zip php-curl php-mbstring php-mysqlnd php-gd php-xml php-intl php-zip

#Install Mariadb
apt install -y mariadb-server
#Bikin databasenya
mysql -e "CREATE DATABASE IF NOT EXISTS laravel_ukk;"
#Bikin usernya
mysql -e "CREATE USER 'laravel_ukk'@'localhost' IDENTIFIED BY '123';"
mysql -e "GRANT ALL PRIVILEGES ON . TO 'laravel_ukk'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

#Install Composer
apt install -y composer

#Clone repository gwehj
git clone https://github.com/GajahBaru-png/cinta.git --branch main  /var/www/ukk

#salin
cp /var/www/ukk/.env.example /var/www/ukk/.env

#Config .env
sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' /var/www/ukk/.env
sed -i 's/^# DB_HOST=.*/DB_HOST=127.0.0.1/' /var/www/ukk/.env
sed -i 's/^# DB_PORT=.*/DB_PORT=3306/' /var/www/ukk/.env
sed -i 's/^# DB_DATABASE=.*/DB_DATABASE=laravel_ukk/' /var/www/ukk/.env
sed -i 's/^# DB_USERNAME=.*/DB_USERNAME=laravel_ukk/' /var/www/ukk/.env
sed -i 's/^# DB_PASSWORD=.*/DB_PASSWORD=123/' /var/www/ukk/.env


#Gasin laravelnya coyyy
cd /var/www/ukk
composer update
php artisan key:gen
php artisan migrate --seed
php artisan migrate:refresh --seed
php artisan shield:gen --all
php artisan make:filament-user

#Setting permission
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

#Install Apache dan konfigurasi virtual host
apt install -y apache2
echo "<VirtualHost *:80>

    ServerAdmin admin5@sija.local
    ServerName ukk5.sija.local
    DocumentRoot /var/www/ukk/public

    <Directory />
            Options FollowSymLinks
            AllowOverride None
    </Directory>
    <Directory /var/www/ukk>
            AllowOverride All
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
" > /etc/apache2/sites-available/ukk.conf
a2ensite ukk.conf
a2enmod rewrite
a2dissite 000-default.conf
service apache2 restart

#Install NPM

apt install -y npm
npm install
npm run build

# restart Apache  anjayyyy
service apache2 restart
# Output ngasih tau kalau selesai
echo "Rampung Ndes"
