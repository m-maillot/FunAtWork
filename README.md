# Fun At Work

Take a break and play at some funny games like soccer table, ping pong, billards, small basket...
This project is here to record and archive all games scoring. An RestAPI is available to access at data and create client.

It's important to have fun at work.

## Installation

It's a very light project. Installation should be easy and require the minimum.

### Requirements

 * PHP version 7 with module pdo and xml

### Clone the project

```
$ git clone https://github.com/m-maillot/FunAtWork.git
$ cd FunAtWork
```

### Install dependencies

```
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

### Create database (SQLite)

```
$ php vendor/bin/doctrine orm:schema-tool:create
```

### Run server

```
php -S localhost:8080 -t public public/index.php
```

## Credits

Developped by a lezard.

## License

FunAtWork is licensed under the [MIT license](LICENSE).
