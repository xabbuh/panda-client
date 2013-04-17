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
    public function getVideosForPagination($page = 1, $per_page = 100)
    {
        return $this->restClient->get(
            "/videos.json",
            array(
                "include_root" => true,
                "page" => $page,
                "per_page" => $per_page
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getVideo($videoId)
    {
        return $this->restClient->get("/videos/$videoId.json");
    }

    /**
     * {@inheritDoc}
     */
    public function getVideoMetadata($videoId)
    {
        return $this->restClient->get("/videos/$videoId/metadata.json");
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
        $filter["status"] = $status;
        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfile($profileId, array $filter = array())
    {
        $filter["profile_id"] = $profileId;
        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfileByName($profileName, array $filter = array())
    {
        $filter["profile_name"] = $profileName;
        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForVideo($videoId, array $filter = array())
    {
        $filter["video_id"] = $videoId;
        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncoding($encodingId)
    {
        return $this->restClient->get("/encodings/$encodingId.json");
    }

    /**
     * {@inheritDoc}
     */
    public function createEncoding($videoId, $profileId)
    {
        return $this->restClient->post(
            "/encodings.json",
            array("video_id" => $videoId, "profile_id" => $profileId,)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function createEncodingWithProfileName($videoId, $profileName)
    {
        return $this->restClient->post(
            "/encodings.json",
            array("video_id" => $videoId, "profile_name" => $profileName,)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function cancelEncoding($encodingId)
    {
        return $this->restClient->post("/encodings/$encodingId/cancel.json");
    }

    /**
     * {@inheritDoc}
     */
    public function retryEncoding($encodingId)
    {
        return $this->restClient->post("/encodings/$encodingId/retry.json");
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEncoding($encodingId)
    {
        return $this->restClient->delete("/encodings/$encodingId.json");
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
    public function addProfile(array $data)
    {
        return $this->restClient->post("/profiles.json", $data);
    }

    /**
     * {@inheritDoc}
     */
    public function addProfileFromPreset($presetName)
    {
        return $this->restClient->post(
            "/profiles.json",
            array("preset_name" => $presetName)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setProfile($profileId, array $data)
    {
        return $this->restClient->put("/profiles/$profileId.json", $data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteProfile($profileId)
    {
        return $this->restClient->delete("/profiles/$profileId.json");
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
