# PHP Commenting System

## To use it you should install php and mysql

```bash

mysql -u root -p [password]

MariaDB [(none)]> CREATE DATABASE `comments` CHARACTER SET = `utf8`;
MariaDB [(none)]> EXIT;

mysql -u root -p < comments.sql

export DATABASE_PASSWORD="Your password"

php -S localhost:8080 -s comments.php

```