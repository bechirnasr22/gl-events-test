GL events Test
========================

Requirements
------------

  * PHP 7.4 or higher
  * Composer
  * Docker
  * Make
  * Symfony CLI

Installation
------------

```bash
$ git clone git@github.com:nasrbechir/gl-events-test.git
$ cd gl-events-test
$ make install
```

Database initialization
------------
The Database initialization will create the database, tables and load fixtures
```bash
$ make init-db
```

Start the appalication
------------
```bash
$ make start
```

Tests
------------
```bash
$ make tests
```