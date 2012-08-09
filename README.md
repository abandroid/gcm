Endroid Google Cloud Messaging
==============================

[![Build Status](https://secure.travis-ci.org/endroid/Gcm.png)](http://travis-ci.org/endroid/Gcm)

Google Cloud Messaging is a service that helps developers send data from servers to their Android applications on
Android devices. See [Google GCM](http://developer.android.com/guide/google/gcm/index.html) for more information.

Requests with messages targeting 1000+ registration ID's will automatically be chunked and sent in parallel in order
to circumvent the maximum imposed by Google. So you don't have to bother about this restriction when using this class.

```php
<?php

$gcm = new Endroid\Gcm();

// Registration ID's of devices to target
$registrationIds = array(
    ...
);

$data = array(
    'title' => 'Message title',
    'message' => 'Message body',
);

$success = $gcm->send($data, $registrationIds);
```