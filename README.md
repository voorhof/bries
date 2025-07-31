# Bries starter kit for Laravel

[![Latest Version](https://img.shields.io/packagist/v/voorhof/bries.svg?style=flat-square)](https://packagist.org/packages/voorhof/bries)
[![Run tests](https://github.com/voorhof/bries/actions/workflows/run-test.yml/badge.svg)](https://github.com/voorhof/bries/actions/workflows/run-test.yml)
[![Code Style](https://img.shields.io/github/actions/workflow/status/voorhof/bries/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/voorhof/bries/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/voorhof/bries.svg?style=flat-square)](https://packagist.org/packages/voorhof/bries)

This is a basic starter kit for Laravel authentication scaffolding using [Bootstrap 5](https://getbootstrap.com/) as the frontend toolkit.  
Heavily inspired on the **breeze and blade** package option from [Laravel Breeze](https://github.com/laravel/breeze).

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

For switching themes when using the dark mode, you can implement a theme toggler of your choice.  
An example of a theme switch toggler can be found on the cheatsheet page, including its styles and scripts.

When you only want to copy the stub files without the node building and database migration steps, use the command below.  
This is useful when implementing this package inside an existing starter-kit build process to speed up the installation.  
For example, inside [voorhof/cms](https://github.com/voorhof/cms) Bries is available as the authentication scaffolding.    
That package has its own building steps, which are executed after copying both Bries and Cms stubs.

```bash
php artisan bries:copy
```

## Credits

- [David Carton](https://github.com/voorhof)
- [Taylor Otwell](https://github.com/taylorotwell) for the big inspiration!
- [All Contributors](https://github.com/voorhof/bries/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
