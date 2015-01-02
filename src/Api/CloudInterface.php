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

use Xabbuh\PandaClient\Model\Cloud as CloudModel;
use Xabbuh\PandaClient\Model\Encoding;
use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Model\Video;
use Xabbuh\PandaClient\Transformer\TransformerRegistryInterface;

/**
 * Interface definition for Panda API implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface CloudInterface
{
    /**
     * Changes the HTTP client used to perform API requests.
     *
     * @param HttpClientInterface $httpClient The HTTP client
     */
    public function setHttpClient(HttpClientInterface $httpClient);

    /**
     * Returns the Panda HTTP client.
     *
     * @return HttpClientInterface The Panda HTTP client
     */
    public function getHttpClient();

    /**
     * Sets the registry that manages the serialization and deserialization
     * of API objects.
     *
     * @param TransformerRegistryInterface $transformers The transformers
     */
    public function setTransformers(TransformerRegistryInterface $transformers);

    /**
     * Returns the Transformer registry.
     *
     * @return TransformerRegistryInterface
     */
    public function getTransformers();

    /**
     * Retrieve a collection of videos from the server.
     *
     * @return Video[] The videos
     */
    public function getVideos();

    /**
     * Retrieve a part of all videos for pagination.
     *
     * This method returns a PHP object with four properties:
     * <ul>
     * <li>videos: the videos</li>
     * <li>page: the current page</li>
     * <li>per_page: number of videos per page (as requested)</li>
     * <li>total: total number of videos</li>
     * </ul>
     *
     * @param int $page     The current page
     * @param int $per_page Number of videos per page
     *
     * @return \stdClass The result object
     */
    public function getVideosForPagination($page = 1, $per_page = 100);

    /**
     * Fetch data of a video.
     *
     * @param string $videoId The video id
     *
     * @return Video The video
     */
    public function getVideo($videoId);

    /**
     * Fetch metadata of a video.
     *
     * @param string $videoId The video id
     *
     * @return string[] The video metadata
     */
    public function getVideoMetadata($videoId);

    /**
     * Delete a video from the server.
     *
     * @param Video $video The video being removed
     *
     * @return string The server response
     */
    public function deleteVideo(Video $video);

    /**
     * Send a request to the Panda encoding service to encode a video file that
     * can be found under a particular url.
     *
     * See the
     * {@link http://www.pandastream.com/docs/api#videos_post_videos_json_optional_parameters Panda API documentation}
     * for a description of the optional parameters
     *
     * @param string $url        The url where the encoding service can fetch
     *                           the video
     * @param array  $profiles   Profile names or profile ids to be used during
     *                           the encoding process
     * @param string $pathFormat The complete video path
     * @param string $payload    Arbitrary string to be stored with the video
     *
     * @return Video The encoded video
     */
    public function encodeVideoByUrl($url, array $profiles = array(), $pathFormat = null, $payload = null);

    /**
     * Upload a video file to the Panda encoding service.
     *
     * See the
     * {@link http://www.pandastream.com/docs/api#videos_post_videos_json_optional_parameters Panda API documentation}
     * for a description of the optional parameters
     *
     * @param string $localPath  The path to the local video file
     * @param array  $profiles   Profile names or profile ids to be used during
     *                           the encoding process
     * @param string $pathFormat The complete video path
     * @param string $payload    Arbitrary string to be stored with the video
     *
     * @return Video The encoded video
     */
    public function encodeVideoFile($localPath, array $profiles = array(), $pathFormat = null, $payload = null);

    /**
     * Register an upload session for a specific file.
     *
     * @param string   $filename       The name of the file to transfer
     * @param string   $fileSize       The size of the file in bytes
     * @param string[] $profiles       Array of profile names for which encodings will be created (by default no encodings will be created)
     * @param bool     $useAllProfiles If true create encodings for all profiles (is only taken in account if the list of profile names is null)
     *
     * @return \stdClass An object containing the id of the video after uploading and a URL to which the file needs to be pushed.
     */
    public function registerUpload($filename, $fileSize, array $profiles = null, $useAllProfiles = false);

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
     * @param string[] $filter Optional set of filters
     *
     * @return Encoding[] The encodings
     */
    public function getEncodings(array $filter = null);

    /**
     * Receive encodings filtered by status from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string   $status Status to filter by (one of 'success', 'fail' or 'processing')
     * @param string[] $filter Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsWithStatus($status, array $filter = null);

    /**
     * Receive encodings filtered by a profile from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param Profile  $profile The profile to filter by
     * @param string[] $filter  Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsForProfile(Profile $profile, array $filter = null);

    /**
     * Receive encodings filtered by a profile id from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string   $profileId Id of the profile to filter by
     * @param string[] $filter    Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsForProfileById($profileId, array $filter = null);

    /**
     * Receive encodings filtered by profile name from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string   $profileName Name of the profile to filter by
     * @param string[] $filter      Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)

     * @return Encoding[] The encodings
     */
    public function getEncodingsForProfileByName($profileName, array $filter = null);

    /**
     * Receive encodings filtered by video from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param Video    $video  Id of the video to filter by
     * @param string[] $filter Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsForVideo(Video $video, array $filter = null);

    /**
     * Gets an encoding.
     *
     * @param string $encodingId Id of the encoding
     *
     * @return Encoding The encoding
     */
    public function getEncoding($encodingId);

    /**
     * Creates an encoding based on a profile.
     *
     * @param Video   $video   The video being encoded
     * @param Profile $profile The profile used to encode the video
     *
     * @return Encoding The new encoding
     */
    public function createEncoding(Video $video, Profile $profile);

    /**
     * Creates an encoding for the profile with the given id.
     *
     * @param Video  $video     The video being encoded
     * @param string $profileId Id of the profile used to encode the video
     *
     * @return Encoding The new encoding
     */
    public function createEncodingWithProfileId(Video $video, $profileId);

    /**
     * Creates an encoding for the profile with the given name.
     *
     * @param Video  $video       The video being encoded
     * @param string $profileName Name of the profile used to encode the video
     *
     * @return Encoding The new encoding
     */
    public function createEncodingWithProfileName(Video $video, $profileName);

    /**
     * Cancel an encoding.
     *
     * @param Encoding $encoding The encoding being canceled
     *
     * @return string The server response
     */
    public function cancelEncoding(Encoding $encoding);

    /**
     * Retry a failed encoding.
     *
     * @param Encoding $encoding The failed encoding
     *
     * @return string The server response
     */
    public function retryEncoding(Encoding $encoding);

    /**
     * Delete an encoding.
     *
     * @param Encoding $encoding The encoding being deleted
     *
     * @return string The server response
     */
    public function deleteEncoding(Encoding $encoding);

    /**
     * Retrieve all profiles.
     *
     * @return Profile[] The profiles
     */
    public function getProfiles();

    /**
     * Retrieve information for a profile.
     *
     * @param string $profileId The id of the profile being fetched
     *
     * @return Profile The profile
     */
    public function getProfile($profileId);

    /**
     * Adds a profile with the given data.
     *
     * @param Profile $profile The profile being added
     *
     * @return Profile The added profile
     */
    public function addProfile(Profile $profile);

    /**
     * Creates a profile based on a given preset.
     *
     * @param string $presetName Name of the preset to use
     *
     * @return Profile The added profile
     */
    public function addProfileFromPreset($presetName);

    /**
     * Changes the data of a profile.
     *
     * @param Profile $profile The profile's new data
     *
     * @return Profile The modified profile
     */
    public function setProfile(Profile $profile);

    /**
     * Delete a profile.
     *
     * @param Profile $profile The profile being removed
     *
     * @return string The server response
     */
    public function deleteProfile(Profile $profile);

    /**
     * Retrieve a cloud's details
     *
     * @param string $cloudId The id of the cloud being fetched
     *
     * @return CloudModel The cloud
     */
    public function getCloud($cloudId = null);

    /**
     * Changes the cloud data.
     *
     * @param array  $data    The cloud's new data
     * @param string $cloudId The id of the cloud being modified
     *
     * @return CloudModel The new cloud
     */
    public function setCloud(array $data, $cloudId = null);

    /**
     * Retrieves the cloud's notifications configuration.
     *
     * @return Notifications The notifications configuration
     */
    public function getNotifications();

    /**
     * Changes the notifications configuration.
     *
     * @param Notifications $notifications The new configuration
     *
     * @return Notifications The modified notifications
     */
    public function setNotifications(Notifications $notifications);
}
