<a href="https://aimeos.org/">
    <img src="https://aimeos.org/fileadmin/template/icons/logo.png" alt="Aimeos logo" title="Aimeos" align="right" height="60" />
</a>

# Aimeos headless distribution

:star: Star us on GitHub — it motivates a lot!

[Aimeos](https://aimeos.org/Laravel) is THE professional, full-featured and
ultra fast headless ecommerce for Laravel!  You can adapt, extend, overwrite
and customize anything to your needs.

## Features

Aimeos is a full-featured e-commerce package:

* JSON REST API based on jsonapi.org
* Multi vendor, multi channel and multi warehouse
* From one to 1,000,000,000+ items
* Extremly fast down to 20ms
* For multi-tentant e-commerce SaaS solutions
* Bundles, vouchers, virtual, configurable, custom and event products
* Subscriptions with recurring payments
* Block/tier pricing out of the box
* Extension for customer/group based prices
* Discount and voucher support
* Flexible basket rule system
* Full-featured admin backend
* Beautiful admin dashboard
* Configurable product data sets
* Completly modular structure
* Extremely configurable and extensible
* Extension for market places with millions of vendors
* Translated to 30+ languages
* Full RTL support
* AI-based text translation
* Secure and reviewed implementation
* High quality source code

... and [more Aimeos features](https://aimeos.org/features)

## Requirements

The Aimeos shop distribution requires:
- Linux/Unix, WAMP/XAMP or MacOS environment
- PHP >= 7.3
- MySQL >= 5.7.8, MariaDB >= 10.2.2
- Web server (Apache, Nginx or integrated PHP web server for testing)

If required PHP extensions are missing, `composer` will tell you about the missing
dependencies.

## Installation

To install the Aimeos shop application, you need [composer 2.1+](https://getcomposer.org).
On the CLI, execute this command for a complete installation including a working setup:

```
wget https://getcomposer.org/download/latest-stable/composer.phar -O composer
php composer create-project aimeos/aimeos-headless headless
```

You will be asked for the parameters of your database and mail server as well as an
e-mail and password used for creating the administration account.

In a local environment, you can use the integrated PHP web server to test your new Aimeos
installation. Simply execute the following command to start the web server:

```
cd headless
php artisan serve
```

**Note:** In an hosting environment, the document root of your virtual host must point to
the **/.../headless/public/** directory and you have to change the `APP_URL` setting in your `.env`
file to your domain without port, e.g.:

```
APP_URL=http://myhostingdomain.com
```

## JSON REST API

After the installation, you can test the Aimeos JSON REST API by calling the URL of your
VHost in your browser. If you use the integrated PHP web server, you should browse
this URL: [http://127.0.0.1:8000/jsonapi](http://127.0.0.1:8000/jsonapi)

Learn how to use the [JSON REST API](https://aimeos.org/docs/latest/frontend/jsonapi/)

## Backend

The Aimeos administration interface will be available at `/admin` in your VHost. When using
the integrated PHP web server, call this URL: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

[![Aimeos admin backend](https://aimeos.org/fileadmin/aimeos.org/images/aimeos-backend.png?2021.04)](http://admin.demo.aimeos.org/)

## Customize

Laravel and the Aimeos headless ecommerce distribution are extremely flexible and highly customizable.
A lot of documentation for the [Laravel framework](https://laravel.com) and the
[Aimeos ecommerce framework](https://aimeos.org/docs/latest/) exists. If you have questions
about Aimeos, don't hesitate to ask in our [Aimeos forum](https://aimeos.org/help/).

## License

The Aimeos shop system is licensed under the terms of the MIT and LGPLv3 license and
is available for free.

## Links

* [Web site](https://aimeos.org/Laravel)
* [Documentation](https://aimeos.org/docs/latest/)
* [Forum](https://aimeos.org/help/)
* [Issue tracker](https://github.com/aimeos/aimeos-headless/issues)
* [Composer packages](https://packagist.org/packages/aimeos/aimeos-headless)
* [Source code](https://github.com/aimeos/aimeos-headless)
