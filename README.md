# easy
Another Simple PHP Framework For Education Purpose!

## requirement

- PHP 7
- DBMS (MySQL, PostgreSQL, etc)

## installation

Restore `easydb.sql` to Your database. 

## configuration

### database connection

Edit it in `app/config.php` file.

### web server

Place .htaccess file in to public directory

```
RewriteEngine on

# If a directory or a file exists, use the request directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
# Otherwise forward the request to index.php
RewriteRule . index.php
```

### nginx

nginx configuration

```
server_name easy.local;
root /var/www/easy/public;
index index.php index.html index.htm;

location / {
      try_files $uri $uri/ /index.php$is_args$args;
}
```

## another

username : admin 
password : 123456

Build with love by [Hafid Mukhlasin](http://hafidmukhlasin.com)

He is author of [Be Fullstack Developer](http://bukularavelvue.com) book (Best Seller!)
