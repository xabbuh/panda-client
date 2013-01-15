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
     * Retrieve a collection of videos from the server.
     *
     * @return string The server response
     */
    public function getVideos()
    {
        return $this->restClient->get("/videos.json");
    }

    /**
     * Delete a video from the server.
     *
     * @param string $videoId The id of the video being removed
     * @return string The server response
     */
    public function deleteVideo($videoId)
    {
        return $this->restClient->delete("/videos/$videoId.json");
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
     * Receive encodings from the server.
     *
     * Filters can be any set of key-value-pairs:
     * <ul>
     *   <li>status: one of 'success', 'fail' or 'processing' to filter by status</li>
     *   <li>profile_id: filter encodings by profile id</li>
     *   <li>profile_name: filter encodings by profile names</li>
     *   <li>video_id: filter by video id
     * </ul>
     *
     * @param array $filter Optional set of filters
     * @return string The server response
     */
    public function getEncodings(array $filter = null)
    {
        return $this->restClient->get("/encodings.json", $filter);
    }

    /**
     * Receive encodings filtered by status from the server.
     *
     * @see PandaApi::getEncodings()
     * @param $status Status to filter by (one of 'success', 'fail' or 'processing')
     * @param array $filter Additional optional filters (see
     *     {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description
     *     of the filters which can be used)
     * @return string The server response
     */
    public function getEncodingsWithStatus($status, array $filter = null)
    {
        return $this->getEncodings(array("status" => $status), $filter);
    }

    /**
     * Receive encodings filtered by a profile id from the server.
     *
     * @see PandaApi::getEncodings()
     * @param $profileId Id of the profile to filter by
     * @param array $filter Additional optional filters (see
     *     {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description
     *     of the filters which can be used)
     * @return string The server response
     */
    public function getEncodingsForProfile($profileId, array $filter = null)
    {
        return $this->getEncodings(array("profile_id" => $profileId), $filter);
    }

    /**
     * Receive encodings filtered by profile name from the server.
     *
     * @see PandaApi::getEncodings()
     * @param $profileName Name of the profile to filter by
     * @param array $filter Additional optional filters (see
     *     {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description
     *     of the filters which can be used)
     * @return string The server response
     */
    public function getEncodingsForProfileByName($profileName, array $filter = null)
    {
        return $this->getEncodings(array("profile_name" => $profileName), $filter);
    }

    /**
     * Receive encodings filtered by video from the server.
     *
     * @see PandaApi::getEncodings()
     * @param $videoId Id of the video to filter by
     * @param array $filter Additional optional filters (see
     *     {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description
     *     of the filters which can be used)
     * @return string The server response
     */
    public function getEncodingsForVideo($videoId, array $filter = null)
    {
        return $this->getEncodings(array("video_id" => $videoId), $filter);
    }

    /**
     * Retrieve all profiles.
     *
     * @return string The server response
     */
    public function getProfiles()
    {
        return $this->restClient->get("/profiles.json");
    }

    /**
     * Retrieve informations for a profile.
     *
     * @param $profileId The id of the profile being fetched
     * @return string The server response
     */
    public function getProfile($profileId)
    {
        return $this->restClient->get("/profiles/$profileId.json");
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
