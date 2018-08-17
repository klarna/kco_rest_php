# Klarna Checkout REST PHP SDK
[![Packagist Version][packagist-image]](https://packagist.org/packages/klarna/kco_rest)
[![Build Status][travis-image]](https://travis-ci.org/klarna/kco_rest_php)
[![Coverage Status][coveralls-image]](https://coveralls.io/r/klarna/kco_rest_php?branch=v2.2)

Klarna Checkout is a revolutionary new payment solution that is changing the way
people shop online. First, consumers verify their purchase with a minimal
amount of information through intelligent identification, securing your order
immediately, and then complete their payment afterwards – separating buying
from paying and dramatically increasing conversion. Klarna Checkout also allows
merchants to offer all payment methods through one supplier, minimizing
administration, costs and integration time.


## Getting started

SDK covers Klarna API: https://developers.klarna.com/api/

### Prerequisites
* PHP 5.5 or above
* [API credentials](#api-credentials)


### API Credentials

Before getting a production account you can get a playground one.
Register here to be able to test your SDK integration before go live:

- https://playground.eu.portal.klarna.com/developer-sign-up - for EU countries
- https://playground.us.portal.klarna.com/developer-sign-up - for the US

Later you need to register as a Klarna merchant to get a production credentials

- https://developers.klarna.com/en/gb/kco-v3




## PHP SDK Installation and Usage

To install the PHP SDK from the Central Composer repository use composer:

```
composer require klarna/kco_rest
```

Highly recommended to use version tag when installing SDK.

```
composer require klarna/kco_rest:1.2.3.4
```

Detailed information about the PHP SDK package and a list of available versions can be found here:
https://packagist.org/packages/klarna/kco_rest

Include the SDK into your PHP file using the Composer autoloader:

```
<?php

require('vendor/autoload.php');
```



## Documentation and Examples

Klarna API documentation: https://developers.klarna.com/api/  
SDK References: https://klarna.github.io/kco_rest_php/


Example files can be found in the [docs/](docs/) directory.  
Additional documentation can be found at https://developers.klarna.com.


## Logging and Debugging

PHP SDK logs information to STDOUT/STDERR. To enable debug mode, set DEBUG_SDK environment variable:

```
$ DEBUG_SDK=true php <your_program.php>
```

or

```
$ export DEBUG_SDK=1
$ php <your_program.php>
```

Another way to enable Debugging Mode is `define` the **DEBUG_SDK** inside your script:

```php
<?php
// some code here
define('DEBUG_SDK', true);
// some code here
```

The output will look like:

```
DEBUG MODE: Request
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    URL : GET /customer-token/v1/tokens/TOKEN
Headers : {"User-Agent":["Library\/Klarna.kco_rest_php_3.1.0 (Guzzle\/6.3.3; curl\/7.54.0) OS\/Darwin_17.5.0 Language\/PHP_5.6.37"],"Authorization":"*SECRET*"}
   Body :

DEBUG MODE: Response
<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
Headers : {"Content-Type":["application\/json"],"Date":["Wed, 15 Aug 2018 15:55:53 GMT"],"Klarna-Correlation-Id":["ABC-123"],"Server":["openresty"],"Content-Length":["62"],"Connection":["keep-alive"]}
   Body : {
     "status" : "ACTIVE",
     "payment_method_type" : "INVOICE"
   }
```


## Questions and feedback
If you have any questions concerning this product or the implementation,
please contact [integration@klarna.com](mailto:integration@klarna.com).


## How to contribute
At Klarna, we strive toward achieving the highest possible quality for our
products. Therefore, we require you to follow these guidelines if you wish
to contribute.

To contribute, the following criteria needs to be fulfilled:

* Description regarding what has been changed and why
* Pull requests should implement a boxed change
* All code and documentation must follow the [PSR-2 standard](http://www.php-fig.org/psr/psr-2/)
* New features and bug fixes must have accompanying unit tests:
    * Positive tests
    * Negative tests
    * Boundary tests (if possible)
    * No less than 90% decision coverage
* All tests should pass


## Acknowledgements
* Christer Gustavsson ([@ChristerGustavsson](https://github.com/ChristerGustavsson))
* David Keijser ([@keis](https://github.com/keis))
* Joakim Löfgren ([@JoakimLofgren](https://github.com/JoakimLofgren))
* Majid Garmaroudi ([@dijam](https://github.com/dijam))
* Omer Karadagli ([@ockcyp](https://github.com/ockcyp))
* Alexander Zinovev ([@alexions](https://github.com/alexions))


## License
Klarna Checkout REST PHP SDK is licensed under
[Apache License, Version 2.0](http://www.apache.org/LICENSE-2.0)

[packagist-image]: https://img.shields.io/packagist/v/klarna/kco_rest.svg?style=flat
[travis-image]: https://img.shields.io/travis/klarna/kco_rest_php/v2.2.svg?style=flat
[coveralls-image]: https://img.shields.io/coveralls/klarna/kco_rest_php/v2.2.svg?style=flat
