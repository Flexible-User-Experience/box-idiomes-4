Box Idiomes
===========

A Symfony 5.4 LTS project to manage [Box Idiomes](https://www.boxidiomes.cat) website content with custom ERP integrated functionalities.

---

#### Installation requirements

* PHP 8.0
* MySQL 8.0
* Git 2.0
* Composer 2.0
* Yarn 1.0
* set php.ini config max_input_vars > 10.000

#### Installation instructions

```bash
$ git clone git@github.com:Flexible-User-Experience/box-idiomes-4.git box-idiomes-4
$ cd box-idiomes-4
$ cp env.dist .env
$ nano .env
$ composer install
$ yarn install
```

Remember to edit `.env` config file according to your system environment needs.

#### Testing suite commands

```bash
$ ./scripts/developer-tools/test-database-reset.sh
$ ./scripts/developer-tools/run-test.sh
```
