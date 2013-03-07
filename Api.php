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
class Api implements ApiInterface
{
    /**
     * The client which is used to perform the requests to the REST api
     * 
     * @var \Xabbuh\PandaClient\RestClientInterface
     */
    private $restClient;
    
    
    /**
     * Constructs the Panda API instance on a given REST client.
     * 
     * @param \Xabbuh\PandaClient\RestClientInterface $restClient The client for REST requests
     */
    public function __construct(RestClientInterface $restClient)
    {
        $this->restClient = $restClient;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRestClient()
    {
        return $this->restClient;
    }

    /**
     * {@inheritDoc}
     */
    public function getVideos()
    {
        return $this->restClient->get("/videos.json");
    }

    /**
     * {@inheritDoc}
     */
    public function deleteVideo($videoId)
    {
        return $this->restClient->delete("/videos/$videoId.json");
    }

    /**
     * {@inheritDoc}
     */
    public function encodeVideoByUrl($url)
    {
        return $this->restClient->post("/videos.json", array("source_url" => $url));
    }

    /**
     * {@inheritDoc}
     */
    public function encodeVideoFile($localPath)
    {
        return $this->restClient->post("/videos.json", array("file" => "@$localPath"));
    }

    /**
     * {@inheritDoc}
     */
    public function registerUpload($filename, $filesize, array $profiles = null, $useAllProfiles = false)
    {
        if (!is_null($profiles)) {
            $options = array(
                "file_name" => $filename,
                "file_size" => $filesize,
                "profiles" => implode(",", $profiles)
            );
        } else {
            $options = array(
                "file_name" => $filename,
                "file_size" => $filesize,
                "use_all_profiles" => $useAllProfiles
            );
        }
        return $this->restClient->post("/videos/upload.json", $options);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodings(array $filter = array())
    {
        return $this->restClient->get("/encodings.json", $filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsWithStatus($status, array $filter = array())
    {
        return $this->getEncodings(array("status" => $status), $filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfile($profileId, array $filter = array())
    {
        return $this->getEncodings(array("profile_id" => $profileId), $filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfileByName($profileName, array $filter = array())
    {
        return $this->getEncodings(array("profile_name" => $profileName), $filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForVideo($videoId, array $filter = array())
    {
        return $this->getEncodings(array("video_id" => $videoId), $filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfiles()
    {
        return $this->restClient->get("/profiles.json");
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile($profileId)
    {
        return $this->restClient->get("/profiles/$profileId.json");
    }

    /**
     * {@inheritDoc}
     */
    public function getCloud($cloudId)
    {
        return $this->restClient->get("/clouds/$cloudId.json");
    }

    /**
     * {@inheritDoc}
     */
    public function setCloud($cloudId, array $data)
    {
        return $this->restClient->put("/clouds/$cloudId.json", $data);
    }

    /**
     * {@inheritDoc}
     */
    public function getNotifications()
    {
        return $this->restClient->get("/notifications.json");
    }

    /**
     * {@inheritDoc}
     */
    public function setNotifications(array $notifications)
    {
        return $this->restClient->put("/notifications.json", $notifications);
    }
}
