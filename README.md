GL events Test
========================


Installation
------------

```bash
$ git clone https://github.com/nasrbechir/gl-events-test
$ cd gl-events-test
$ make install
```

Database configuration
------------
The database connection configuration are stored in the DATABASE_URL variable that exists in the .env (you can create your own .env.local) file.

Example : DATABASE_URL=‘mysql://db_user:db_password@127.0.0.1:3306/db_name’

Mailler configuration
------------
The mailler connection configuration are stored in the MAILER_DSN variable that exists in the .env file.

Example : MAILER_DSN=smtp://127.0.0.1:1025?encryption=tls&auth_mode=login


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