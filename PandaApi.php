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
 * Intuitive PHP interface for the Panda video encoding service API.
 * 
 * The implementation provides methodes for accessing all endpoints of the Panda
 * encoding REST webservice. Each method is mapped to a corresponding HTTP
 * request.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class PandaApi
{
    /**
     * The client which is used to perform the requests to the REST api
     * 
     * @var \Xabbuh\PandaClient\PandaRestClient
     */
    private $restClient;
    
    
    /**
     * Constructs the Panda API instance on a given REST client.
     * 
     * @param \Xabbuh\PandaClient\PandaRestClient $restClient The client for REST requests
     */
    public function __construct(PandaRestClient $restClient)
    {
        $this->restClient = $restClient;
    }
    
    /**
     * Returns the Panda REST client.
     * 
     * @return \Xabbuh\PandaClient\PandaRestClient The Panda REST client
     */
    public function getRestClient()
    {
        return $this->restClient;
    }
    
    /**
     * Send a request to the Panda encoding service to encode a video file that
     * can be found under a particular url.
     * 
     * @param string $url The url where the encoding service can fetch the video
     * @return string The server response
     */
    public function encodeVideoByUrl($url)
    {
        return $this->restClient->post("/videos.json", array("source_url" => $url));
    }
    
    /**
     * Upload a video file to the Panda encoding service.
     * 
     * @param string $localPath The path to the local video file
     * @return string The server response
     */
    public function encodeVideoFile($localPath)
    {
        return $this->restClient->post("/videos.json", array("file" => "@$localPath"));
    }

    /**
     * Retrieve a cloud's details
     *
     * @param $cloudId The id of the cloud being fetched
     * @return string The server response
     */
    public function getCloud($cloudId)
    {
        return $this->restClient->get("/clouds/$cloudId.json");
    }

    /**
     * Change cloud data.
     *
     * @param $cloudId
     * @param array $data
     * @return string The server response
     */
    public function setCloud($cloudId, array $data)
    {
        return $this->restClient->put("/clouds/$cloudId.json", $data);
    }
    
    /**
     * Retrieve the cloud's notifications configuration.
     * 
     * @return string The server response
     */
    public function getNotifications()
    {
        return $this->restClient->get("/notifications.json");
    }
    
    /**
     * Change the notifications configuration.
     * 
     * @param array $notifications The new configuration
     * @return string The server response
     */
    public function setNotifications(array $notifications)
    {
        return $this->restClient->put("/notifications.json", $notifications);
    }
}
