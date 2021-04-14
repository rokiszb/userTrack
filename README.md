User Task list
=================================

This repository holds code to basic symfony task list. There are currently two solutions. Datatables is on main branch
and separate data-tables_solution branch, and another solution is on symfony-solution branch, check out both.
```
Symfony 4.4
jQuery 3.6
JS DataTables
Bootstrap 4
SQLITE database

PHP 7.4
```

## Setup

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

**Setup the Database**

Open `.env` and make sure the `DATABASE_URL` setting is
correct for your system.

Then, create the database and the schema!

```
php bin/console doctrine:schema:create
```

**Start the web server**

You can use Nginx or Apache, but the built-in web server works
great:

```
php -S localhost:8000 -t public
```
or if you have symfony CLI installed:
```
symfony serve
```

Now check out the site at `http://localhost:8000`
