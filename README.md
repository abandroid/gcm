Endroid Google Cloud Messaging
==============================

*By [endroid](http://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/gcm.svg)](https://packagist.org/packages/endroid/gcm)
[![Build Status](https://secure.travis-ci.org/endroid/Gcm.png)](http://travis-ci.org/endroid/Gcm)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/gcm.svg)](https://packagist.org/packages/endroid/gcm)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/gcm.svg)](https://packagist.org/packages/endroid/gcm)
[![License](http://img.shields.io/packagist/l/endroid/gcm.svg)](https://packagist.org/packages/endroid/gcm)

Google Cloud Messaging is a service that helps developers send data from servers to their Android applications on
Android devices. See [Google GCM](http://developer.android.com/guide/google/gcm/index.html) for more information.

Requests with messages targeting 1000+ registration ID's will automatically be chunked and sent in parallel in order
to circumvent the maximum imposed by Google. So you don't have to bother about this restriction when using this class.

```php
<?php

use Endroid\Gcm\Client;

$client = new Client($apiKey);

// Registration ID's of devices to target
$registrationIds = array(
    ...
);

$data = array(
    'title' => 'Message title',
    'message' => 'Message body',
);

$success = $client->send($data, $registrationIds);

```

If something went wrong or if you just want to inspect the response objects returned by the GCM server, you can retrieve
these using the getResponses() method.

## Symfony

You can use [`EndroidGcmBundle`](https://github.com/endroid/EndroidGcmBundle) to enable this service in your Symfony application.

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
