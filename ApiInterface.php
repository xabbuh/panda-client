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

use Xabbuh\PandaClient\Model\Cloud;
use Xabbuh\PandaClient\Model\Encoding;
use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Model\Video;

/**
 * Interface definition for Panda API implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ApiInterface
{
    /**
     * Returns the Panda REST client.
     *
     * @return RestClientInterface The Panda REST client
     */
    public function getRestClient();

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
     * @return array The video metadata
     */
    public function getVideoMetadata($videoId);

    /**
     * Delete a video from the server.
     *
     * @param string $videoId The id of the video being removed
     *
     * @return string The server response
     */
    public function deleteVideo($videoId);

    /**
     * Send a request to the Panda encoding service to encode a video file that
     * can be found under a particular url.
     *
     * @param string $url The url where the encoding service can fetch the video
     *
     * @return Video The encoded video
     */
    public function encodeVideoByUrl($url);

    /**
     * Upload a video file to the Panda encoding service.
     *
     * @param string $localPath The path to the local video file
     *
     * @return Video The encoded video
     */
    public function encodeVideoFile($localPath);

    /**
     * Register an upload session for a specific file.
     *
     * @param string  $filename       The name of the file to transfer
     * @param string  $fileSize       The size of the file in bytes
     * @param array   $profiles       Array of profile names for which encodings will be created (by default no encodings will be created)
     * @param boolean $useAllProfiles If true create encodings for all profiles (is only taken in account if the list of profile names is null)
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
     * @param array $filter Optional set of filters
     *
     * @return Encoding[] The encodings
     */
    public function getEncodings(array $filter = null);

    /**
     * Receive encodings filtered by status from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string $status Status to filter by (one of 'success', 'fail' or 'processing')
     * @param array  $filter Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsWithStatus($status, array $filter = null);

    /**
     * Receive encodings filtered by a profile id from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string $profileId Id of the profile to filter by
     * @param array  $filter    Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsForProfile($profileId, array $filter = null);

    /**
     * Receive encodings filtered by profile name from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string $profileName Name of the profile to filter by
     * @param array  $filter      Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)

     * @return Encoding[] The encodings
     */
    public function getEncodingsForProfileByName($profileName, array $filter = null);

    /**
     * Receive encodings filtered by video from the server.
     *
     * @see PandaApi::getEncodings()
     *
     * @param string $videoId Id of the video to filter by
     * @param array  $filter  Additional optional filters (see {@link PandaApi::getEncodings() PandaApi::getEncodings()} for a description of the filters which can be used)
     *
     * @return Encoding[] The encodings
     */
    public function getEncodingsForVideo($videoId, array $filter = null);

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
     * @param string $videoId   Id of the video being encoded
     * @param string $profileId Id of the profile used to encode the video
     *
     * @return Encoding The new encoding
     */
    public function createEncoding($videoId, $profileId);

    /**
     * Create an encoding for the profile with the given name.
     *
     * @param string $videoId     Id of the video being encoded
     * @param string $profileName Name of the profile used to encode the video
     *
     * @return Encoding The encoding
     */
    public function createEncodingWithProfileName($videoId, $profileName);

    /**
     * Cancel an encoding.
     *
     * @param string $encodingId The id of the encoding being canceled
     *
     * @return string The server response
     */
    public function cancelEncoding($encodingId);

    /**
     * Retry a failed encoding.
     *
     * @param string $encodingId The id of the failed encoding
     *
     * @return string The server response
     */
    public function retryEncoding($encodingId);

    /**
     * Delete an encoding.
     *
     * @param string $encodingId The id of the encoding being deleted
     *
     * @return string The server response
     */
    public function deleteEncoding($encodingId);

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
     * @param array $data The new profile's data
     *
     * @return Profile The added profile
     */
    public function addProfile(array $data);

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
     * @param Profile $profileId The profile being removed
     *
     * @return string The server response
     */
    public function deleteProfile(Profile $profileId);

    /**
     * Retrieve a cloud's details
     *
     * @param string $cloudId The id of the cloud being fetched
     *
     * @return Cloud The cloud
     */
    public function getCloud($cloudId);

    /**
     * Changes the cloud data.
     *
     * @param string $cloudId The id of the cloud being modified
     * @param array  $data    The cloud's new data
     *
     * @return Cloud The new cloud
     */
    public function setCloud($cloudId, array $data);

    /**
     * Retrieves the cloud's notifications configuration.
     *
     * @return Notifications The notifications configuration
     */
    public function getNotifications();

    /**
     * Changes the notifications configuration.
     *
     * @param array $notifications The new configuration
     *
     * @return Notifications The modified notifications
     */
    public function setNotifications(array $notifications);
}
