<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Gcm;

use Buzz\Browser;
use Buzz\Client\MultiCurl;

class Client
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
     * @var string
     */
    protected $registrationIdMaxCount = 1000;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var array
     */
    protected $responses;

    /**
     * Class constructor.
     *
     * @param $apiKey
     * @param null $apiUrl
     */
    public function __construct($apiKey, $apiUrl = null)
    {
        $this->apiKey = $apiKey;

        if ($apiUrl) {
            $this->apiUrl = $apiUrl;
        }

        $this->browser = new Browser(new MultiCurl());
        $this->browser->getClient()->setVerifyPeer(false);
    }

    /**
     * Sends the data to the given registration ID's via the GCM server.
     *
     * @param mixed $data
     * @param array $registrationIds
     * @param array $options         to add along with message, such as collapse_key, time_to_live, delay_while_idle
     *
     * @return bool
     */
    public function send($data, array $registrationIds, array $options = array())
    {
        $headers = array(
            'Authorization: key='.$this->apiKey,
            'Content-Type: application/json',
        );

        $data = array_merge($options, array(
            'data' => $data,
        ));

        // Chunk number of registration ID's according to the maximum allowed by GCM
        $chunks = array_chunk($registrationIds, $this->registrationIdMaxCount);

        // Perform the calls (in parallel)
        $this->responses = array();
        foreach ($chunks as $registrationIds) {
            $data['registration_ids'] = $registrationIds;
            $this->responses[] = $this->browser->post($this->apiUrl, $headers, json_encode($data));
        }
        $this->browser->getClient()->flush();

        // Determine success
        foreach ($this->responses as $response) {
            $message = json_decode($response->getContent());
            if ($message === null || $message->success == 0 || $message->failure > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
