# Nova Sortable Table Resource

[![Build Status](https://travis-ci.org/wqa/nova-sortable-table-resource.svg?branch=master)](https://travis-ci.org/wqa/nova-sortable-table-resource)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wqa/nova-sortable-table-resource/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wqa/nova-sortable-table-resource/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/wqa/nova-sortable-table-resource/badge.svg?branch=master)](https://coveralls.io/github/wqa/nova-sortable-table-resource?branch=master)

[![Packagist](https://img.shields.io/packagist/v/wqa/nova-sortable-table-resource.svg)](https://packagist.org/packages/wqa/nova-sortable-table-resource)
[![Packagist](https://poser.pugx.org/wqa/nova-sortable-table-resource/d/total.svg)](https://packagist.org/packages/wqa/nova-sortable-table-resource)
[![Packagist](https://img.shields.io/packagist/l/wqa/nova-sortable-table-resource.svg)](https://packagist.org/packages/wqa/nova-sortable-table-resource)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require wqa/nova-sortable-table-resource
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Wqa\NovaSortableTableResource\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Wqa\NovaSortableTableResource\Facades\NovaSortableTableResource::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Wqa\NovaSortableTableResource\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email adam.lee@wearewqa.com
instead of using the issue tracker.

## Credits

- [Adam Lee](https://github.com/wqa/nova-sortable-table-resource)
- [All contributors](https://github.com/wqa/nova-sortable-table-resource/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
