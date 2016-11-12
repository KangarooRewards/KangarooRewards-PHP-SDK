# Kangaroo Rewards REST API SDK for PHP

[![Build Status](https://travis-ci.org/KangarooRewards/KangarooRewards-PHP-SDK.png?branch=master)](https://travis-ci.org/KangarooRewards/KangarooRewards-PHP-SDK)
[![Latest Version](https://img.shields.io/github/release/KangarooRewards/KangarooRewards-PHP-SDK.svg?style=flat-square)](https://github.com/KangarooRewards/KangarooRewards-PHP-SDK/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

## Requirements

The following versions of PHP are supported.

* PHP 5.6

## Installation

Add the following to your `composer.json` file.

```json
{
    "require": {
        "kangaroorewards/KangarooRewards-PHP-SDK": "~1.0"
    }
}
```

## Usage

```php

    $api = new KangarooApi([
        'access_token' => {ACCESS_TOKEN},
    ]);

    $resourceOwner = $api->me();

    $customer = $api->getCustomer({CUSTOMER_ID}, ['include' => ['balance']]); 

    $offers = $api->getOffers();

```

## Credits

- [Valentin Ursuleac](https://github.com/ursuleacv)

## License

The MIT License (MIT). Please see [License File](https://github.com/KangarooRewards/KangarooRewards-PHP-SDK/blob/master/LICENSE.md) for more information.
