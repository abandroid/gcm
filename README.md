Endroid Google Cloud Messaging
==============================

*By [endroid](http://endroid.nl/)*

[![Build Status](https://secure.travis-ci.org/endroid/Gcm.png)](http://travis-ci.org/endroid/Gcm)
[![Latest Stable Version](https://poser.pugx.org/endroid/gcm/v/stable.png)](https://packagist.org/packages/endroid/gcm)
[![Total Downloads](https://poser.pugx.org/endroid/gcm/downloads.png)](https://packagist.org/packages/endroid/gcm)

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

## License

This bundle is under the MIT license. For the full copyright and license information, please view the LICENSE file that
was distributed with this source code.
