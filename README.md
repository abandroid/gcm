Endroid Google Cloud Messaging
==============================

[![Build Status](https://secure.travis-ci.org/endroid/gcm.png)](http://travis-ci.org/endroid/gcm)

Google Cloud Messaging is a service that helps developers send data from servers to their Android applications on
Android devices. See [Google GCM](http://developer.android.com/guide/google/gcm/index.html) for more information.

```php
<?php

$gcm = new Endroid\Browser();

$registrationIds = array(
    // Registration ID's of devices to target
);

$data = array(
    'title' => 'Message title',
    'message' => 'Message body',
);

$response = $gcm->send($data, $registrationIds);
```