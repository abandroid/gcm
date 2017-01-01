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
use Buzz\Message\Response;

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
     * @var MultiCurl
     */
    protected $client;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var Response[]
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

        $this->client = new MultiCurl();
        $this->client->setVerifyPeer(false);
        $this->browser = new Browser($this->client);
    }

    /**
     * Sends the message via the GCM server.
     *
     * @param mixed $data
     * @param array $registrationIds
     * @param array $options
     *
     * @return bool
     */
    public function send($data, array $registrationIds = [], array $options = [])
    {
        $this->responses = [];

        $data = array_merge($options, [
            'data' => $data,
        ]);

        if (isset($options['to'])) {
            $this->responses[] = $this->browser->post($this->apiUrl, $this->getHeaders(), json_encode($data));
        } elseif (count($registrationIds) > 0) {
            // Chunk number of registration ID's according to the maximum allowed by GCM
            $chunks = array_chunk($registrationIds, $this->registrationIdMaxCount);
            // Perform the calls (in parallel)
            foreach ($chunks as $registrationIds) {
                $data['registration_ids'] = $registrationIds;
                $this->responses[] = $this->browser->post($this->apiUrl, $this->getHeaders(), json_encode($data));
            }
        }

        $this->client->flush();

        foreach ($this->responses as $response) {
            $message = json_decode($response->getContent());
            if ($message === null || $message->success == 0 || $message->failure > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sends the data to the given registration token, notification key, or topic via the GCM server.
     *
     * @param mixed  $data
     * @param string $topic   The value must be a registration token, notification key, or topic. Default global topic.
     * @param array  $options to add along with message, such as collapse_key, time_to_live, delay_while_idle
     *
     * @return bool
     */
    public function sendTo($data, $topic = '/topics/global', array $options = [])
    {
        $options['to'] = $topic;

        return $this->send($data, [], $options);
    }

    /**
     * Returns the headers.
     *
     * @return array
     */
    protected function getHeaders()
    {
        $headers = [
            'Authorization: key='.$this->apiKey,
            'Content-Type: application/json',
        ];

        return $headers;
    }

    /**
     * Returns the responses.
     *
     * @return Response[]
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
