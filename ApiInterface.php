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
 * Interface definition for Panda API implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ApiInterface
{
    /**
     * Returns the Panda REST client.
     *
     * @return \Xabbuh\PandaClient\RestClientInterface The Panda REST client
     */
    public function getRestClient();

    /**
     * Retrieve a collection of videos from the server.
     *
     * @return string The server response
     */
    public function getVideos();

    /**
     * Delete a video from the server.
     *
     * @param string $videoId The id of the video being removed
     * @return string The server response
     */
    public function deleteVideo($videoId);

    /**
     * Send a request to the Panda encoding service to encode a video file that
     * can be found under a particular url.
     *
     * @param string $url The url where the encoding service can fetch the video
     * @return string The server response
     */
    public function encodeVideoByUrl($url);

    /**
     * Upload a video file to the Panda encoding service.
     *
     * @param string $localPath The path to the local video file
     * @return string The server response
     */
    public function encodeVideoFile($localPath);

    /**
     * Register an upload session for a specific file.
     *
     * @param string $filename The name of the file to transfer
     * @param string $filesize The size of the file in bytes
     * @param array $profiles Array of profile names for which encodings will
     * be created (by default no encodings will be created)
     * @param bool $useAllProfiles If true create encodings for all profiles
     * (is only taken in account if the list of profile names is null)
     * @return string A JSON encoded object containing the id of the video
     * after uploading and a URL to which the file needs to be pushed.
     */
    public function registerUpload($filename, $filesize, array $profiles = null, $useAllProfiles = false);

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
    public function getEncodings(array $filter = null);

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
    public function getEncodingsWithStatus($status, array $filter = null);

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
    public function getEncodingsForProfile($profileId, array $filter = null);

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
    public function getEncodingsForProfileByName($profileName, array $filter = null);

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
    public function getEncodingsForVideo($videoId, array $filter = null);

    /**
     * Retrieve all profiles.
     *
     * @return string The server response
     */
    public function getProfiles();

    /**
     * Retrieve informations for a profile.
     *
     * @param $profileId The id of the profile being fetched
     * @return string The server response
     */
    public function getProfile($profileId);

    /**
     * Retrieve a cloud's details
     *
     * @param $cloudId The id of the cloud being fetched
     * @return string The server response
     */
    public function getCloud($cloudId);

    /**
     * Change cloud data.
     *
     * @param $cloudId
     * @param array $data
     * @return string The server response
     */
    public function setCloud($cloudId, array $data);

    /**
     * Retrieve the cloud's notifications configuration.
     *
     * @return string The server response
     */
    public function getNotifications();

    /**
     * Change the notifications configuration.
     *
     * @param array $notifications The new configuration
     * @return string The server response
     */
    public function setNotifications(array $notifications);
}
