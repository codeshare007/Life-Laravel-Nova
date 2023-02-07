# Nova Extend Resources

[![Build Status](https://travis-ci.org/wqa/nova-extend-resources.svg?branch=master)](https://travis-ci.org/wqa/nova-extend-resources)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wqa/nova-extend-resources/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wqa/nova-extend-resources/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/wqa/nova-extend-resources/badge.svg?branch=master)](https://coveralls.io/github/wqa/nova-extend-resources?branch=master)

[![Packagist](https://img.shields.io/packagist/v/wqa/nova-extend-resources.svg)](https://packagist.org/packages/wqa/nova-extend-resources)
[![Packagist](https://poser.pugx.org/wqa/nova-extend-resources/d/total.svg)](https://packagist.org/packages/wqa/nova-extend-resources)
[![Packagist](https://img.shields.io/packagist/l/wqa/nova-extend-resources.svg)](https://packagist.org/packages/wqa/nova-extend-resources)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require wqa/nova-extend-resources
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
wqa\NovaExtendResources\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
wqa\NovaExtendResources\Facades\NovaExtendResources::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="wqa\NovaExtendResources\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email 
instead of using the issue tracker.

## Credits

- [](https://github.com/wqa/nova-extend-resources)
- [All contributors](https://github.com/wqa/nova-extend-resources/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
