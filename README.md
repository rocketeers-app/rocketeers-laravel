# Rocketeers for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rocketeers-app/rocketeers-laravel.svg?style=flat-square)](https://packagist.org/packages/rocketeers-app/rocketeers-laravel)
[![Build Status](https://img.shields.io/travis/rocketeers-app/rocketeers-laravel/master.svg?style=flat-square)](https://travis-ci.org/rocketeers-app/rocketeers-laravel)
[![Quality Score](https://img.shields.io/scrutinizer/g/rocketeers-app/rocketeers-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocketeers-app/rocketeers-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/rocketeers-app/rocketeers-laravel.svg?style=flat-square)](https://packagist.org/packages/rocketeers-app/rocketeers-laravel)

Laravel integration package with Rocketeers app.

## Installation

You can install this package via Composer:

```bash
composer require rocketeers-app/rocketeers-laravel
```

Configure 'rocketeers' in your 'stack' logging configuration, so you keep your normal logging with additional Rocketeers logging:

```php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['rocketeers', 'daily'],
        'ignore_exceptions' => false,
    ],

    'rocketeers' => [
        'driver' => 'rocketeers',
        'level' => 'debug',
    ],
```

Make sure that in the logging configuration the default log channel is 'stack':

```php
'default' => env('LOG_CHANNEL', 'stack'),
```

Add the Rocketeers config file to your Laravel app:

```php
<?php

return [
    'rocketeers.api_token' => env('ROCKETEERS_API_TOKEN'),
];
```

Add the `ROCKETEERS_API_TOKEN` in your `.env` file.

Done!

### Testing



### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mark@vaneijk.co instead of using the issue tracker.

## Credits

- [Mark van Eijk](https://github.com/markvaneijk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
