<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Api;

use Xabbuh\PandaClient\Exception\ApiException;
use Xabbuh\PandaClient\Exception\HttpException;

/**
 * Panda REST client implementation using the PHP cURL extension.
 * 
 * Send signed requests (GET, POST, PUT or DELETE) to the Panda encoding
 * webservice.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class RestClient implements RestClientInterface
{
    /**
     * Panda cloud id
     * 
     * @var string
     */
    private $cloudId;
    
    /**
     * API access key
     * 
     * @var string
     */
    private $accessKey;
    
    /**
     * API secret key
     * 
     * @var string
     */
    private $secretKey;
    
    /**
     * API host
     * 
     * @var string
     */
    private $apiHost;
    
    
    /**
     * Constructs the Panda REST client.
     * 
     * @param string  $cloudId Panda cloud id
     * @param Account $account The account used to authorise requests
     */
    public function __construct($cloudId, Account $account)
    {
        $this->cloudId = $cloudId;
        $this->accessKey = $account->getAccessKey();
        $this->secretKey = $account->getSecretKey();
        $this->apiHost = $account->getApiHost();
    }

    /**
     * {@inheritDoc}
     */
    public function getCloudId()
    {
        return $this->cloudId;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getApiHost()
    {
        return $this->apiHost;
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $params = array())
    {
        return $this->request('GET', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, array $params = array())
    {
        return $this->request('POST', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, array $params = array())
    {
        return $this->request('PUT', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, array $params = array())
    {
        return $this->request('DELETE', $path, $params);
    }
    
    /**
     * Send signed HTTP requests to the API server.
     * 
     * @param string $method HTTP method (GET, POST, PUT or DELETE)
     * @param string $path   Request path
     * @param array  $params Additional request parameters
     *
     * @return string The API server's response
     *
     * @throws ApiException if an API error occurs
     * @throws HttpException if the request fails
     */
    private function request($method, $path, array $params)
    {
        // sign the request parameters
        $params = $this->signParams($method, $path, $params);
        
        // build url, append url parameters if the request method is GET or DELETE
        $url = 'https://'.$this->apiHost.'/v2'.$path;
        if ($method == 'GET' || $method == 'DELETE') {
            $url .= '?' . http_build_query($params);
        }
            
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // include parameters in the request body for POST and PUT requests
        if ($method == 'POST' || $method == 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        
        $response = curl_exec($ch);

        // throw exception if the http request failed
        if (curl_errno($ch) > 0) {
            throw new HttpException(curl_error($ch), curl_errno($ch));
        }

        // check for positive api answers
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode < 200 || $httpStatusCode > 207) {
            // throw api exception otherwise
            $decodedResponse = json_decode($response);
            $message = "{$decodedResponse->error}: {$decodedResponse->message}";
            throw new ApiException($message, $httpStatusCode);
        }

        curl_close($ch);
        
        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function signParams($method, $path, array $params)
    {
        // add authentication data to the request parameters if not set
        if (!isset($params['cloud_id'])) {
            $params['cloud_id'] = $this->cloudId;
        }
        if (!isset($params['access_key'])) {
            $params['access_key'] = $this->accessKey;
        }
        if (!isset($params['timestamp'])) {
            $oldTz = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $params['timestamp'] = date('c');
            date_default_timezone_set($oldTz);
        }
        
        // generate the signature
        $params['signature'] = $this->signature($method, $path, $params);
        
        return $params;
    }

    /**
     * {@inheritDoc}
     */
    public function signature($method, $path, array $params)
    {
        ksort($params);
        if (isset($params['file'])) {
            unset($params['file']);
        }
        $canonicalQueryString = str_replace(
            array('+', '%5B', '%5D'),
            array('%20', '[', ']'),
            http_build_query($params)
        );
        $stringToSign = sprintf("%s\n%s\n%s\n%s", strtoupper($method), $this->apiHost, $path, $canonicalQueryString);
        $hmac = hash_hmac('sha256', $stringToSign, $this->secretKey, true);
        return base64_encode($hmac);
    }
}
