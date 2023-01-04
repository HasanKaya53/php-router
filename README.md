

# PHP Router

simple and fast php page router.

#How Install

```bash
 composer require luckystar/php-router
```

Plase add .htacces file:


## Features

- GET/POST Methods
- Simple To Use
- Easily import php or html files
- You cant use parameters. :(

upcoming features
- Add Parameters.
- Add Middleware


## Usage/Examples


```php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

Set Theme folder
```php
LuckyStar\PhpRouter\BHRouter::$themeFolder = "src/themes/";
```

Calling php when the index request comes
```php
LuckyStar\PhpRouter\BHRouter::get("/", 'index.php');
```

Calling php when the about request comes
```php
LuckyStar\PhpRouter\BHRouter::get("/about", 'about-page.php');
```

Calling function when the index request comes
```php
 LuckyStar\PhpRouter\BHRouter::post("/", function(){
    echo "test";
});
```
pass variables

```php
$pages = ['index','header','footer'];
LuckyStar\PhpRouter\BHRouter::get("/", 'index.php', $pages);
```


404 pages usage: (should be added to the end of the page. )
```php
LuckyStar\PhpRouter\BHRouter::noOne("/404", function(){
    echo "there is no such page";
});
```

