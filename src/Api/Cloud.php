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

use Xabbuh\PandaClient\Model\Encoding;
use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Model\Video;
use Xabbuh\PandaClient\Transformer\TransformerRegistryInterface;

/**
 * Object-oriented interface to easily access a Panda cloud.
 *
 * The implementation provides methods for accessing all endpoints of the Panda
 * encoding REST webservice. Each method is mapped to a corresponding HTTP
 * request.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Cloud implements CloudInterface
{
    /**
     * The client which is used to perform the requests to the REST api
     *
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var TransformerRegistryInterface
     */
    private $transformers;

    /**
     * {@inheritDoc}
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * {@inheritDoc}
     */
    public function setTransformers(TransformerRegistryInterface $transformers)
    {
        $this->transformers = $transformers;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    /**
     * {@inheritDoc}
     */
    public function getVideos()
    {
        $response = $this->httpClient->get('/videos.json');
        $transformer = $this->transformers->getVideoTransformer();

        return $transformer->stringToVideoCollection($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getVideosForPagination($page = 1, $per_page = 100)
    {
        $response = $this->httpClient->get(
            '/videos.json',
            array(
                'include_root' => true,
                'page' => $page,
                'per_page' => $per_page
            )
        );
        $transformer = $this->transformers->getVideoTransformer();
        $result = json_decode($response);
        $result->videos = $transformer->stringToVideoCollection(json_encode($result->videos));

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getVideo($videoId)
    {
        $response = $this->httpClient->get('/videos/'.$videoId.'.json');
        $transformer = $this->transformers->getVideoTransformer();

        return $transformer->stringToVideo($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getVideoMetadata($videoId)
    {
        $response = $this->httpClient->get('/videos/'.$videoId.'/metadata.json');

        return get_object_vars(json_decode($response));
    }

    /**
     * {@inheritDoc}
     */
    public function deleteVideo(Video $video)
    {
        return $this->httpClient->delete('/videos/'.$video->getId().'.json');
    }

    /**
     * {@inheritDoc}
     */
    public function encodeVideoByUrl($url)
    {
        $response = $this->httpClient->post('/videos.json', array('source_url' => $url));
        $transformer = $this->transformers->getVideoTransformer();

        return $transformer->stringToVideo($response);
    }

    /**
     * {@inheritDoc}
     */
    public function encodeVideoFile($localPath)
    {
        $response = $this->httpClient->post('/videos.json', array('file' => '@'.$localPath));
        $transformer = $this->transformers->getVideoTransformer();

        return $transformer->stringToVideo($response);
    }

    /**
     * {@inheritDoc}
     */
    public function registerUpload($filename, $fileSize, array $profiles = null, $useAllProfiles = false) {
        if (null !== $profiles) {
            $options = array(
                'file_name' => $filename,
                'file_size' => $fileSize,
                'profiles' => implode(',', $profiles)
            );
        } else {
            $options = array(
                'file_name' => $filename,
                'file_size' => $fileSize,
                'use_all_profiles' => $useAllProfiles
            );
        }

        return json_decode($this->httpClient->post('/videos/upload.json', $options));
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodings(array $filter = array())
    {
        $response = $this->httpClient->get('/encodings.json', $filter);
        $transformer = $this->transformers->getEncodingTransformer();

        return $transformer->stringToEncodingCollection($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsWithStatus($status, array $filter = array())
    {
        $filter['status'] = $status;

        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfile(Profile $profile, array $filter = array())
    {
        $filter['profile_id'] = $profile->getId();

        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfileById($profileId, array $filter = null)
    {
        // TODO: implement
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForProfileByName($profileName, array $filter = array())
    {
        $filter['profile_name'] = $profileName;

        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingsForVideo(Video $video, array $filter = array())
    {
        $filter['video_id'] = $video->getId();

        return $this->getEncodings($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncoding($encodingId)
    {
        $response = $this->httpClient->get('/encodings/'.$encodingId.'.json');
        $transformer = $this->transformers->getEncodingTransformer();

        return $transformer->stringToEncoding($response);
    }

    /**
     * {@inheritDoc}
     */
    public function createEncoding(Video $video, Profile $profile)
    {
        $response = $this->httpClient->post(
            '/encodings.json',
            array('video_id' => $video->getId(), 'profile_id' => $profile->getId())
        );
        $transformer = $this->transformers->getEncodingTransformer();

        return $transformer->stringToEncoding($response);
    }

    /**
     * {@inheritDoc}
     */
    public function createEncodingWithProfileId(Video $video, $profileId)
    {
        // TODO: implement
    }

    /**
     * {@inheritDoc}
     */
    public function createEncodingWithProfileName(Video $video, $profileName)
    {
        $response = $this->httpClient->post(
            '/encodings.json',
            array('video_id' => $video->getId(), 'profile_name' => $profileName,)
        );
        $transformer = $this->transformers->getEncodingTransformer();

        return $transformer->stringToEncoding($response);
    }

    /**
     * {@inheritDoc}
     */
    public function cancelEncoding(Encoding $encoding)
    {
        return $this->httpClient->post('/encodings/'.$encoding->getId().'/cancel.json');
    }

    /**
     * {@inheritDoc}
     */
    public function retryEncoding(Encoding $encoding)
    {
        return $this->httpClient->post('/encodings/'.$encoding->getId().'/retry.json');
    }

    /**
     * {@inheritDoc}
     */
    public function deleteEncoding(Encoding $encoding)
    {
        return $this->httpClient->delete('/encodings/'.$encoding->getId().'.json');
    }

    /**
     * {@inheritDoc}
     */
    public function getProfiles()
    {
        $response = $this->httpClient->get('/profiles.json');
        $transformer = $this->transformers->getProfileTransformer();

        return $transformer->stringToProfileCollection($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile($profileId)
    {
        $response = $this->httpClient->get('/profiles/'.$profileId.'.json');
        $transformer = $this->transformers->getProfileTransformer();

        return $transformer->stringToProfile($response);
    }

    /**
     * {@inheritDoc}
     */
    public function addProfile(Profile $profile)
    {
        $transformer = $this->transformers->getProfileTransformer();
        $requestParameters = $transformer->toRequestParams($profile);
        $response = $this->httpClient->post('/profiles.json', $requestParameters->all());

        return $transformer->stringToProfile($response);
    }

    /**
     * {@inheritDoc}
     */
    public function addProfileFromPreset($presetName)
    {
        $response = $this->httpClient->post(
            '/profiles.json',
            array('preset_name' => $presetName)
        );
        $transformer = $this->transformers->getProfileTransformer();

        return $transformer->stringToProfile($response);
    }

    /**
     * {@inheritDoc}
     */
    public function setProfile(Profile $profile)
    {
        $transformer = $this->transformers->getProfileTransformer();
        $response = $this->httpClient->put(
            '/profiles/'.$profile->getId().'.json',
            $transformer->toRequestParams($profile)->all()
        );

        return $transformer->stringToProfile($response);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteProfile(Profile $profile)
    {
        return $this->httpClient->delete('/profiles/'.$profile->getId().'.json');
    }

    /**
     * {@inheritDoc}
     */
    public function getCloud($cloudId = null)
    {
        if (null === $cloudId) {
            $cloudId = $this->httpClient->getCloudId();
        }

        $response = $this->httpClient->get('/clouds/'.$cloudId.'.json');
        $transformer = $this->transformers->getCloudTransformer();

        return $transformer->stringToCloud($response);
    }

    /**
     * {@inheritDoc}
     */
    public function setCloud(array $data, $cloudId = null)
    {
        if (null === $cloudId) {
            $cloudId = $this->httpClient->getCloudId();
        }

        $response = $this->httpClient->put('/clouds/'.$cloudId.'.json', $data);
        $transformer = $this->transformers->getCloudTransformer();

        return $transformer->stringToCloud($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getNotifications()
    {
        $response = $this->httpClient->get('/notifications.json');
        $transformer = $this->transformers->getNotificationsTransformer();

        return $transformer->stringToNotifications($response);
    }

    /**
     * {@inheritDoc}
     */
    public function setNotifications(Notifications $notifications)
    {
        $transformer = $this->transformers->getNotificationsTransformer();
        $data = $transformer->toRequestParams($notifications)->all();
        $response = $this->httpClient->put('/notifications.json', $data);

        return $transformer->stringToNotifications($response);
    }
}
