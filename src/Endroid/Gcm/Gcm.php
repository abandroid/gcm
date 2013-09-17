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
     * @var string
     */
    protected $registrationIdMaxCount = 1000;

    /**
     * @var \Buzz\Browser
     */
    protected $browser;

    /**
     * @var array
     */
    protected $responses;

    /**
     * Sent registration ids 
     * @var array
     */
    protected $registrationIds = array();

    /**
     * Associatives array for id => errors
     * @var array
     */
    protected $registrationErrors;

    /**
     * Class constructor
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
    }

    /**
     * Sends the data to the given registration ID's via the GCM server
     *
     * @param mixed $data
     * @param array $registrationIds
     * @param array $options to add along with message, such as collapse_key, time_to_live, delay_while_idle
     * @return bool
     */
    public function send($data, array $registrationIds, array $options = array())
    {
        $this->registrationIds = $registrationIds;
        
        $headers = array(
            'Authorization: key='.$this->apiKey,
            'Content-Type: application/json'
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
     * Return Ids associated with errors
     *
     * @return array RegistrationIds => Error
     */
    public function getRegistrationIdsAssociatedResponse(){
        $this->registrationErrors = array();
        $i = 0;
        foreach ($this->responses as $response) {
            $message = json_decode($response->getContent());
            if ($message !== null && isset($message->results)){
                foreach($message->results as $result){
                    if (isset($this->registrationIds[$i]) && isset($result->error)){
                        $device_id = $this->registrationIds[$i];
                        $this->registrationErrors[$device_id] = $result->error;
                        $i++;
                    }
                }
            }
        }
        return $this->registrationErrors;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
