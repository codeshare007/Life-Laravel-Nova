# Nova Sortable Toggle Fields

[![Build Status](https://travis-ci.org/wqa/nova-sortable-toggle-fields.svg?branch=master)](https://travis-ci.org/wqa/nova-sortable-toggle-fields)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wqa/nova-sortable-toggle-fields/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wqa/nova-sortable-toggle-fields/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/wqa/nova-sortable-toggle-fields/badge.svg?branch=master)](https://coveralls.io/github/wqa/nova-sortable-toggle-fields?branch=master)

[![Packagist](https://img.shields.io/packagist/v/wqa/nova-sortable-toggle-fields.svg)](https://packagist.org/packages/wqa/nova-sortable-toggle-fields)
[![Packagist](https://poser.pugx.org/wqa/nova-sortable-toggle-fields/d/total.svg)](https://packagist.org/packages/wqa/nova-sortable-toggle-fields)
[![Packagist](https://img.shields.io/packagist/l/wqa/nova-sortable-toggle-fields.svg)](https://packagist.org/packages/wqa/nova-sortable-toggle-fields)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require wqa/nova-sortable-toggle-fields
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Wqa\NovaSortableToggleFields\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Wqa\NovaSortableToggleFields\Facades\NovaSortableToggleFields::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Wqa\NovaSortableToggleFields\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email 
instead of using the issue tracker.

## Credits

- [](https://github.com/wqa/nova-sortable-toggle-fields)
- [All contributors](https://github.com/wqa/nova-sortable-toggle-fields/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
