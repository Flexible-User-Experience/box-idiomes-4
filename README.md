Box Idiomes
===========

A Symfony 4 webapp project to manage [Box Idiomes](http://www.boxidiomes.cat) website and custom made ERP.

---

#### Installation requirements

* PHP 7.2
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

#### Load database fixtures commands

```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
$ php bin/console hautelook:fixtures:load
```

#### Testing suite commands

```bash
$ ./scripts/developer-tools/test-database-reset.sh
$ ./scripts/developer-tools/run-test.sh
```
