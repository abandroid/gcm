<?php

namespace Endroid\Gcm;

use Buzz\Browser;
use Buzz\Client\Curl;

class Gcm
{
    /**
     * @var string
     */
    protected $apiUrl = 'https://android.googleapis.com/gcm/send';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Class constructor
     *
     * @param $apiKey
     * @param null $baseUrl
     */
    public function __construct($apiKey, $apiUrl = null)
    {
        $this->apiKey    = $apiKey;

        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }
    }

    public function send($data, $registrationIds)
    {
        $headers = array(
            'Authorization: key='.$this->apiKey,
            'Content-Type: application/json'
        );

        $data = array(
            'registration_ids' => $registrationIds,
            'data' => $data,
        );

        $buzz = new Browser(new Curl());
        $response = $buzz->post($this->apiUrl, $headers, json_encode($data));

        return $response;
    }
}