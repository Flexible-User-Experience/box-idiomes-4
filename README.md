Box Idiomes
===========

A Symfony 4.4 LTS project to manage [Box Idiomes](http://www.boxidiomes.cat) website content with custom ERP integrated functionalities.

---

#### Installation requirements

* PHP 7.4
* MySQL 5.7
* Git
* Composer
* Yarn

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
