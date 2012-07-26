<?php

namespace Endroid\Gcm;

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

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}