# Bries starter kit for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/voorhof/bries.svg?style=flat-square)](https://packagist.org/packages/voorhof/bries)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/voorhof/bries/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/voorhof/bries/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/voorhof/bries.svg?style=flat-square)](https://packagist.org/packages/voorhof/bries)

This is a basic starter kit for Laravel authentication scaffolding using [Bootstrap 5](https://getbootstrap.com/) as the frontend toolkit.  
Heavily inspired on the breeze and blade package option from [Laravel Breeze](https://github.com/laravel/breeze).

## Installation

This package should only be used within a fresh Laravel installation.  
You can install the package via composer:

```bash
composer require --dev voorhof/bries
```

Run this command to set up the starter kit,  
it will copy all necessary resource files to your app and update existing ones:

```bash
php artisan bries:install
```

When you choose to include the CSS grid or dark mode, it will set a root variable.   
This can always be updated after installation inside the bootstrap.scss file:

```scss
$enable-cssgrid: false;
$enable-dark-mode: false;
```

For using the dark mode and switching themes, you can implement a theme toggler of your choice.  
An example of a theme switch toggler can be found on the cheatsheet page, including its styles and scripts.

## Credits

- [David Carton](https://github.com/voorhof)
- [Taylor Otwell](https://github.com/taylorotwell) for the big inspiration!
- [All Contributors](https://github.com/voorhof/bries/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
