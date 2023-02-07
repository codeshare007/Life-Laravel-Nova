# Pdf Extract

[![Build Status](https://travis-ci.org/wqa/pdf-extract.svg?branch=master)](https://travis-ci.org/wqa/pdf-extract)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wqa/pdf-extract/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wqa/pdf-extract/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/wqa/pdf-extract/badge.svg?branch=master)](https://coveralls.io/github/wqa/pdf-extract?branch=master)

[![Packagist](https://img.shields.io/packagist/v/wqa/pdf-extract.svg)](https://packagist.org/packages/wqa/pdf-extract)
[![Packagist](https://poser.pugx.org/wqa/pdf-extract/d/total.svg)](https://packagist.org/packages/wqa/pdf-extract)
[![Packagist](https://img.shields.io/packagist/l/wqa/pdf-extract.svg)](https://packagist.org/packages/wqa/pdf-extract)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require wqa/pdf-extract
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Wqa\PdfExtract\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Wqa\PdfExtract\Facades\PdfExtract::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="Wqa\PdfExtract\ServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email 
instead of using the issue tracker.

## Credits

- [](https://github.com/wqa/pdf-extract)
- [All contributors](https://github.com/wqa/pdf-extract/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
