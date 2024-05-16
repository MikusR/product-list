# Product list

[Test instance](https://sjdtt.mikusr.info/)

Application for listing products

![](images/main.png "Main screen of application")

## Requirements

- PHP 7.4
- MySQL (tested on 8.0.35)

## Install

clone repository

```bash
$ git clone https://github.com/MikusR/product-list.git
```

use Composer to get dependencies

```bash
$ composer install
```

copy .env.example to .env
and configure access to database
for example:

```ini
DBNAME = "swjd"
DBUSER = "root"
PASSWORD = "root"
HOST = "127.0.0.1"
PORT = 3377
DRIVER = "pdo_mysql"
```

create database

```bash
mysql> CREATE DATABASE swjd;
```

run locally with

```bash
php -S localhost:9876 -t ./public
```

or using apache (example apacheconf)

```apacheconf
<VirtualHost *:80>
     ServerAdmin admin@site1.your_domain
     ServerName sjdtt.mikusr.info
     DocumentRoot /var/ww/sjdtt.mikusr.info/public

     <Directory /var/ww/sjdtt.mikusr.info/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
     </Directory>

     ErrorLog ${APACHE_LOG_DIR}/sjdtt.mikusr.info_error.log
     CustomLog ${APACHE_LOG_DIR}/sjdtt.mikusr.info_access.log combined
</VirtualHost>
```

on first visit it is necessary to visit yourserver/migrate to create table and add sample data

```
localhost:9876/migrate
```

## Functionality

### delete

![](images/delete.png "Delete multiple products")

### add new product

Each product type has different attributes

![](images/dvd.png "You can add DVD")
![](images/book.png "You can add Book")
![](images/furniture.png "You can add Furniture")

There are validation checks

![](images/sku_exists.png "If a sku already exists")
![](images/negative_price.png "Negative price entered")