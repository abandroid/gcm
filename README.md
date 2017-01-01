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
use Endroid\Gcm\Client;

$apiKey = '...';
$client = new Client($apiKey);

// Registration ID's of devices to target
$registrationIds = [
    //...
];

$data = [
    'title' => 'Message title',
    'message' => 'Message body',
];

$success = $client->send($data, $registrationIds);
```

If something went wrong or if you just want to inspect the response objects returned by the GCM server, you can retrieve
these using the getResponses() method.

## Symfony integration

Register the Symfony bundle in the kernel.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\Gcm\Bundle\EndroidGcmBundle(),
    ];
}
```

The default parameters can be overridden via the configuration.

```yaml
endroid_gcm:
    api_key: '...'
```

Now you can retrieve the client as follows.

```php
$client = $this->get('endroid.gcm.client');
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
