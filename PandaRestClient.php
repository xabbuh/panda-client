<?php

/*
* This file is part of the XabbuhPandaClient package.
*
* (c) Christian Flothmann <christian.flothmann@xabbuh.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Xabbuh\PandaClient;

/**
 * Panda REST client implementation.
 * 
 * Send signed requests (GET, POST, PUT or DELETE) to the Panda encoding
 * webservice.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class PandaRestClient
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
     * @param string $cloudId Panda cloud id
     * @param string $accessKey API access key
     * @param string $secretKey API secret key
     * @param string $apiHost  API host
     */
    public function __construct($cloudId, $accessKey, $secretKey, $apiHost)
    {
        $this->cloudId = $cloudId;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->apiHost = $apiHost;
    }
    
    /**
     * Helper method to send GET requests to the Panda API.
     * 
     * @param string $path The path to send requests to
     * @param array $params Url parameters
     * @return string The server response
     */
    public function get($path, array $params = array())
    {
        return $this->request("GET", $path, $params);
    }
    
    /**
     * Helper method to send POST requests to the Panda API.
     * 
     * @param string $path The path to send requests to
     * @param array $params Url parameters
     * @return string The server response
     */
    public function post($path, array $params = array())
    {
        return $this->request("POST", $path, $params);
    }
    
    /**
     * Helper method to send PUT requests to the Panda API.
     * 
     * @param string $path The path to send requests to
     * @param array $params Parameters
     * @return string The server response
     */
    public function put($path, array $params = array())
    {
        return $this->request("PUT", $path, $params);
    }
    
    /**
     * Helper method to send DELETE requests to the Panda API.
     * 
     * @param string $path The path to send requests to
     * @param array $params Parameters
     * @return string The server response
     */
    public function delete($path, array $params = array())
    {
        return $this->request("DELETE", $path, $params);
    }
    
    /**
     * Send signed HTTP requests to the API server.
     * 
     * @param string $method HTTP method (GET, POST, PUT or DELETE)
     * @param string $path Request path
     * @param array $params Additional request parameters
     * @return string The API server's response
     */
    private function request($method, $path, array $params)
    {
        // sign the request parameters
        $params = $this->signParams($method, $path, $params);
        
        // build url, append url parameters if the request method is GET or DELETE
        $url = "https://{$this->apiHost}/v2{$path}";
        if ($method == "GET" || $method == "DELETE") {
            $url .= "?" . http_build_query($params);
        }
            
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // include parameters in the request body for POST and PUT requests
        if ($method == "POST" || $method == "PUT") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
    
    /**
     * Generate signature for a given set of request parameters and add the
     * signature to the parameters.
     * 
     * @param string $method The HTTP method
     * @param string $path The request path
     * @param array $params Request parameters
     * @return array The signed parameters 
     */
    public function signParams($method, $path, array $params)
    {
        // add authentication data to the request parameters if not set
        if (!isset($params["cloud_id"])) {
            $params["cloud_id"] = $this->cloudId;
        }
        if (!isset($params["access_key"])) {
            $params["access_key"] = $this->accessKey;
        }
        if (!isset($params["timestamp"])) {
            $oldTz = date_default_timezone_get();
            date_default_timezone_set("UTC");
            $params["timestamp"] = date("c");
            date_default_timezone_set($oldTz);
        }
        
        // generate the signature
        $params["signature"] = $this->signature($method, $path, $params);
        
        return $params;
    }
    
    /**
     * Generates the signature for an API requests based on its parameters.
     * 
     * @param string $method The HTTP method
     * @param string $path The request path
     * @param array $params Request parameters
     * @return string The generated signature
     */
    public function signature($method, $path, array $params)
    {
        ksort($params);
        if (isset($params["file"])) {
            unset($params["file"]);
        }
        $canonicalQueryString = str_replace(
            array("+", "%5B", "%5D"),
            array("%20", "[", "]"),
            http_build_query($params)
        );
        $stringToSign = sprintf("%s\n%s\n%s\n%s", strtoupper($method), $this->apiHost, $path, $canonicalQueryString);
        $hmac = hash_hmac("sha256", $stringToSign, $this->secretKey, true);
        return base64_encode($hmac);
    }
}
