![DBeetle](https://github.com/FranklyRocks/dbeetle/assets/65699701/54e623ce-db71-4a84-8733-34977dbaa9db)

## Features
- Easly query your database
- Secure access with password (also your password is remembered between sessions)
- Queries log
- SQL Editor auto resizable
- Mobile friendly
- Extremely lightweight (4,58 KB vs 38,5 KB of phpMiniAdmin)

## How to Install
- Download [dbeetle.php](https://raw.githubusercontent.com/FranklyRocks/dbeetle/main/dbeetle.php) file
  - example: `wget https://raw.githubusercontent.com/FranklyRocks/dbeetle/main/dbeetle.php`
- Copy/Upload it to your webserver public directory (www or public_html or whatever...)
- Open in your browser `http://yoursite.com/dbeetle.php`

**Recommended:** For additional security you may edit dbeetle.php file and set some password (see `$PASSWORD` variable)

### Dependencies for your database
- MySQL: `sudo apt-get install php-mysql` on Debian or enable `extension=pdo_mysql` in php.ini on Windows
- SQLite: `sudo apt-get install php-sqlite3` on Debian or enable `extension=pdo_sqlite` in php.ini on Windows
- Postgres: `sudo apt-get install php-pgsql` on Debian or enable `extension=pdo_pgsql` in php.ini on Windows

## Config
Set ``$PASSWORD``, ``$LOG_PATH``, and ``$PDO`` based on your environment requirementes.

```php
$PASSWORD = "";
$LOG_PATH = "queries.json";
$PDO = new PDO("sqlite:app.db");
```

