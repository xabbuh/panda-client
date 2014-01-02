<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Api;

use Xabbuh\PandaClient\Api\Cloud;
use Xabbuh\PandaClient\Api\RestClientInterface;
use Xabbuh\PandaClient\Model\Cloud as CloudModel;
use Xabbuh\PandaClient\Model\NotificationEvent;
use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Model\Video;
use Xabbuh\PandaClient\Transformer\CloudTransformer;
use Xabbuh\PandaClient\Transformer\EncodingTransformer;
use Xabbuh\PandaClient\Transformer\NotificationsTransformer;
use Xabbuh\PandaClient\Transformer\ProfileTransformer;
use Xabbuh\PandaClient\Transformer\TransformerRegistry;
use Xabbuh\PandaClient\Transformer\VideoTransformer;

/**
 * Tests for the api implementation.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClientInterface
     */
    private $restClient;

    /**
     * @var TransformerRegistryInterface
     */
    private $transformers;

    /**
     * @var Cloud
     */
    private $api;

    protected function setUp()
    {
        $this->createRestClient();
        $this->createTransformers();
        $this->api = new Cloud($this->restClient, $this->transformers);
    }

    public function testGetRestClient()
    {
        $this->assertEquals($this->restClient, $this->api->getRestClient());
    }

    public function testGetVideos()
    {
        $this->request('get', '/videos.json', $this->createVideosResponse());
        $collection = $this->api->getVideos();
        $this->validateCollection($collection, 'Xabbuh\PandaClient\Model\Video', 2);
    }

    public function testGetVideosForPaginationWithDefaultParameters()
    {
        $this->requestVideosForPagination(1, 100);
        $result = $this->api->getVideosForPagination();
        $this->validateVideosForPagination($result, 1, 100);
    }

    public function testGetVideosForPaginationWithPageParameter()
    {
        $this->requestVideosForPagination(5, 100);
        $result = $this->api->getVideosForPagination(5);
        $this->validateVideosForPagination($result, 5, 100);
    }

    public function testGetVideosForPaginationWithPageAndPerPageParameters()
    {
        $this->requestVideosForPagination(7, 25);
        $result = $this->api->getVideosForPagination(7, 25);
        $this->validateVideosForPagination($result, 7, 25);
    }

    public function testGetVideo()
    {
        $videoId = md5(uniqid());
        $response = '{
          "id":"'.$videoId.'",
          "original_filename":"test.mp4",
          "extname":".mp4",
          "path":"'.$videoId.'",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":240,
          "width":300,
          "fps":29,
          "duration":14000,
          "file_size":39458349,
          "created_at":"2009/10/13 19:11:26 +0100",
          "updated_at":"2009/10/13 19:11:26 +0100"
        }';
        $this->request(
            'get',
            '/videos/'.$videoId.'.json',
        $response);
        $video = $this->api->getVideo($videoId);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Video', $video);
        $this->assertEquals($videoId, $video->getId());
    }

    public function testGetVideoMetadata()
    {
        $videoId = md5(uniqid());
        $response = '{
          "image_height":208,
          "audio_format":"mp4a",
          "selection_time":"0 s",
          "track_layer":0,
          "poster_time":"0 s",
          "video_frame_rate":30.0,
          "duration":"19.35 s",
          "media_create_date":"Tue Jan 28 20:59:44 +0000 1913",
          "audio_sample_rate":48000,
          "compressor_id":"avc1",
          "graphics_mode":"srcCopy",
          "audio_channels":2,
          "media_header_version":0,
          "track_modify_date":"Tue Jan 28 20:59:39 +0000 1913",
          "preferred_volume":"100.00%",
          "mime_type":"video/mp4",
          "file_size":"1435 kB",
          "create_date":"Tue Jan 28 20:59:39 +0000 1913",
          "rotation":0
        }';
        $this->request('get', '/videos/'.$videoId.'/metadata.json', $response);
        $metadata = $this->api->getVideoMetadata($videoId);
        $this->assertTrue(is_array($metadata));
        $this->assertEquals(208, $metadata['image_height']);
        $this->assertEquals('mp4a', $metadata['audio_format']);
        $this->assertEquals('0 s', $metadata['selection_time']);
    }

    public function testDeleteVideo()
    {
        $id = md5(uniqid());
        $this->request('delete', '/videos/'.$id.'.json', 'status: 200');
        $this->assertEquals('status: 200', $this->api->deleteVideo($id));
    }

    public function testEncodeVideoByUrl()
    {
        $url = 'http://www.example.com/video.mp4';
        $this->request(
            'post',
            '/videos.json',
            $this->createVideoResponse(),
            array('source_url' => $url)
        );
        $video = $this->api->encodeVideoByUrl($url);
        $this->validateVideo($video);
    }

    public function testEncodeVideoFile()
    {
        $filename = "video.mp4";
        $this->request('post',
            '/videos.json',
            $this->createVideoResponse(),
            array('file' => '@'.$filename)
        );
        $video = $this->api->encodeVideoFile($filename);
        $this->validateVideo($video);
    }

    public function testRegisterUpload()
    {
        $id = md5(uniqid());
        $location = 'http://example.com/'.$id;
        $response = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $options = array(
            'file_name' => 'video.mp4',
            'file_size' => 114373213,
            'use_all_profiles' => false,
        );
        $this->request('post', '/videos/upload.json', $response, $options);
        $upload = $this->api->registerUpload('video.mp4', 114373213);
        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testRegisterUploadWithProfilesList()
    {
        $id = md5(uniqid());
        $location = 'http://example.com/'.$id;
        $response = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $profiles = array('profile1', 'profile2');
        $options = array(
            'file_name' => 'video.mpg',
            'file_size' => 114373213,
            'profiles' => implode(',', $profiles),
        );
        $this->request('post', '/videos/upload.json', $response, $options);
        $upload = $this->api->registerUpload('video.mpg', 114373213, $profiles);
        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testRegisterUploadWithAllProfiles()
    {
        $id = md5(uniqid());
        $location = "http://example.com/$id";
        $filename = "video.mpg";
        $filesize = 114373213;
        $response = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $options = array(
            "file_name" => $filename,
            "file_size" => $filesize,
            "use_all_profiles" => true,
        );
        $this->request('post', '/videos/upload.json', $response, $options);
        $upload = $this->api->registerUpload($filename, $filesize, null, true);
        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testGetEncodings()
    {
        $this->request('get', '/encodings.json', $this->createEncodingsResponse(), array());
        $encodings = $this->api->getEncodings();
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsWithFilter()
    {
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('status' => 'success')
        );
        $encodings = $this->api->getEncodings(array('status' => 'success'));
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsWithStatus()
    {
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('status' => 'success')
        );
        $encodings = $this->api->getEncodingsWithStatus("success");
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsWithStatusAndFilter()
    {
        $videoId = md5(uniqid());
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('status' => 'success', 'video_id' => $videoId)
        );
        $encodings = $this->api->getEncodingsWithStatus(
            'success',
            array('video_id' => $videoId)
        );
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForProfile()
    {
        $profileId = md5(uniqid());
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('profile_id' => $profileId)
        );
        $encodings = $this->api->getEncodingsForProfile($profileId);
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForProfileWithFilter()
    {
        $profileId = md5(uniqid());
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('profile_id' => $profileId, 'status' => 'success')
        );
        $encodings = $this->api->getEncodingsForProfile(
            $profileId,
            array('status' => 'success')
        );
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForProfileByName()
    {
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('profile_name' => 'h264')
        );
        $encodings = $this->api->getEncodingsForProfileByName('h264');
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForProfileByNameWithFilter()
    {
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('profile_name' => 'h264', 'status' => 'success')
        );
        $encodings = $this->api->getEncodingsForProfileByName(
            'h264',
            array('status' => 'success')
        );
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForVideo()
    {
        $videoId = md5(uniqid());
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('video_id' => $videoId)
        );
        $encodings = $this->api->getEncodingsForVideo($videoId);
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncodingsForVideoWithFilter()
    {
        $videoId = md5(uniqid());
        $this->request(
            'get',
            '/encodings.json',
            $this->createEncodingsResponse(),
            array('video_id' => $videoId, 'status' => 'success')
        );
        $encodings = $this->api->getEncodingsForVideo(
            $videoId,
            array('status' => 'success')
        );
        $this->validateCollection(
            $encodings,
            'Xabbuh\PandaClient\Model\Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testGetEncoding()
    {
        $encodingId = md5(uniqid());
        $videoId = md5(uniqid());
        $profileId = md5(uniqid());
        $this->request(
            'get',
            '/encodings/'.$encodingId.'.json',
            $this->createEncodingResponse($encodingId, $videoId, $profileId)
        );
        $encoding = $this->api->getEncoding($encodingId);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Encoding', $encoding);
        $this->assertEquals($encodingId, $encoding->getId());
    }

    public function testCreateEncoding()
    {
        $videoId = md5(uniqid());
        $profileId = md5(uniqid());
        $encodingId = md5(uniqid());
        $video = new Video();
        $video->setId($videoId);
        $profile = new Profile();
        $profile->setId($profileId);
        $this->request(
            'post',
            '/encodings.json',
            $this->createEncodingResponse($encodingId, $videoId, $profileId),
            array('video_id' => $videoId, 'profile_id' => $profileId)
        );
        $encoding = $this->api->createEncoding($videoId, $profileId);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Encoding', $encoding);
        $this->assertEquals($encodingId, $encoding->getId());
        $this->assertEquals($videoId, $encoding->getVideoId());
        $this->assertEquals($profileId, $encoding->getProfileId());
    }

    public function testCreateEncodingWithProfileName()
    {
        $videoId = md5(uniqid());
        $profileId = md5(uniqid());
        $encodingId = md5(uniqid());
        $profileName = 'h264';
        $video = new Video();
        $video->setId($videoId);
        $this->request(
            'post',
            '/encodings.json',
            $this->createEncodingResponse($encodingId, $videoId, $profileId),
            array('video_id' => $videoId, 'profile_name' => $profileName)
        );
        $encoding = $this->api->createEncodingWithProfileName($videoId, $profileName);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Encoding', $encoding);
        $this->assertEquals($encodingId, $encoding->getId());
        $this->assertEquals($videoId, $encoding->getVideoId());
        $this->assertEquals($profileId, $encoding->getProfileId());
    }

    public function testCancelEncoding()
    {
        $encodingId = md5(uniqid());
        $this->request(
            'post',
            '/encodings/'.$encodingId.'/cancel.json',
            'status: 200'
        );
        $response = $this->api->cancelEncoding($encodingId);
        $this->assertEquals("status: 200", $response);
    }

    public function testRetryEncoding()
    {
        $encodingId = md5(uniqid());
        $this->request(
            'post',
            '/encodings/'.$encodingId.'/retry.json',
            'status: 200'
        );
        $response = $this->api->retryEncoding($encodingId);
        $this->assertEquals("status: 200", $response);
    }

    public function testDeleteEncoding()
    {
        $encodingId = md5(uniqid());
        $this->request(
            'delete',
            '/encodings/'.$encodingId.'.json',
            'status: 200'
        );
        $response = $this->api->deleteEncoding($encodingId);
        $this->assertEquals("status: 200", $response);
    }

    public function testGetProfiles()
    {
        $response = '[{
           "id":"40d9f8711d64aaa74f88462e9274f39a",
           "title":"MP4 (H.264)",
           "name": "h264",
           "extname":".mp4",
           "width":320,
           "height":240,
           "audio_bitrate": 128,
           "video_bitrate": 500,
           "aspect_mode": "letterbox",
           "command":"ffmpeg -i $input_file$ -c:a libfaac $audio_bitrate$ -c:v libx264 $video_bitrate$ -preset medium $filters$ -y $output_file$",
           "created_at":"2009/10/14 18:36:30 +0000",
           "updated_at":"2009/10/14 19:38:42 +0000"
        },
        {
           "id":"c629221e4595928f7f6838bfd30469c3",
           "title":"WebM (VP8)",
           "name": "vp8",
           "extname":".webm",
           "width":640,
           "height":480,
           "audio_bitrate": 128,
           "video_bitrate": "auto",
           "aspect_mode": "letterbox",
           "created_at":"2013/02/06 11:02:49 +0000",
           "updated_at":"2013/02/06 11:02:49 +0000"
        }]';
        $this->request('get', '/profiles.json', $response);
        $profiles = $this->api->getProfiles();
        $this->validateCollection(
            $profiles,
            'Xabbuh\PandaClient\Model\Profile',
            2,
            array('40d9f8711d64aaa74f88462e9274f39a', 'c629221e4595928f7f6838bfd30469c3')
        );
    }

    public function testGetProfile()
    {
        $id = md5(uniqid());
        $this->request(
            'get',
            '/profiles/'.$id.'.json',
            $this->createProfileResponse()
        );
        $profile = $this->api->getProfile($id);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Profile', $profile);
        $this->assertEquals('40d9f8711d64aaa74f88462e9274f39a', $profile->getId());
        $this->assertEquals('H264 (MP4)', $profile->getTitle());
        $this->assertEquals('h264', $profile->getName());
        $this->assertEquals('.mp4', $profile->getExtname());
        $this->assertEquals(320, $profile->getWidth());
        $this->assertEquals(240, $profile->getHeight());
        $this->assertEquals(128, $profile->getAudioBitrate());
        $this->assertEquals(500, $profile->getVideoBitrate());
        $this->assertEquals('letterbox', $profile->getAspectMode());
        $this->assertEquals(
            'ffmpeg -i $input_file$ -c:a libfaac $audio_bitrate$ -c:v libx264 $video_bitrate$ -preset medium $filters$ -y $output_file$',
            $profile->getCommand()
        );
        $this->assertEquals('2009/10/14 18:36:30 +0000', $profile->getCreatedAt());
        $this->assertEquals('2009/10/14 19:38:42 +0000', $profile->getUpdatedAt());
    }

    public function testAddProfile()
    {
        $this->request(
            'post',
            '/profiles.json',
            $this->createProfileResponse(),
            array()
        );
        $profile = $this->api->addProfile(array());
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Profile', $profile);
        $this->assertEquals('40d9f8711d64aaa74f88462e9274f39a', $profile->getId());
        $this->assertEquals('H264 (MP4)', $profile->getTitle());
        $this->assertEquals('h264', $profile->getName());
        $this->assertEquals('.mp4', $profile->getExtname());
    }

    public function testAddProfileFromPreset()
    {
        $this->request(
            'post',
            '/profiles.json',
            $this->createProfileResponse(),
            array('preset_name' => 'h264')
        );
        $profile = $this->api->addProfileFromPreset('h264');
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Profile', $profile);
        $this->assertEquals('40d9f8711d64aaa74f88462e9274f39a', $profile->getId());
        $this->assertEquals('H264 (MP4)', $profile->getTitle());
        $this->assertEquals('h264', $profile->getName());
        $this->assertEquals('.mp4', $profile->getExtname());
    }

    public function testSetProfile()
    {
        $profile = new Profile();
        $profile->setId('40d9f8711d64aaa74f88462e9274f39a');
        $profile->setTitle('H264 (MP4)');
        $profile->setName('h264');
        $profile->setExtname('.mp4');
        $profile->setWidth(320);
        $profile->setHeight(240);
        $profile->setAudioBitrate(128);
        $profile->setVideoBitrate(500);
        $profile->setAspectMode('letterbox');
        $profile->setCommand(
            'ffmpeg -i $input_file$ -c:a libfaac $audio_bitrate$ -c:v libx264 $video_bitrate$ -preset medium $filters$ -y $output_file$'
        );
        $profile->setCreatedAt('2009/10/14 18:36:30 +0000');
        $profile->setUpdatedAt('2009/10/14 19:38:42 +0000');
        $this->request(
            'put',
            '/profiles/40d9f8711d64aaa74f88462e9274f39a.json',
            $this->createProfileResponse(),
            $this->isType('array')
        );
        $modifiedProfile = $this->api->setProfile($profile);
        $this->assertEquals($profile, $modifiedProfile);
    }

    public function testDeleteProfile()
    {
        $profile = new Profile();
        $profile->setId('40d9f8711d64aaa74f88462e9274f39a');
        $this->request(
            'delete',
            '/profiles/40d9f8711d64aaa74f88462e9274f39a.json',
            'status: 200'
        );
        $this->assertEquals('status: 200', $this->api->deleteProfile($profile));
    }

    public function testGetCloud()
    {
        $id = md5(uniqid());
        $this->request('get', '/clouds/'.$id.'.json', $this->createCloudResponse());
        $cloud = $this->api->getCloud($id);
        $this->validateCloud($cloud);
    }

    public function testGetCloudWithoutId()
    {
        $this->request(
            'get',
            '/clouds/'.$this->restClient->getCloudId().'.json',
            $this->createCloudResponse()
        );
        $cloud = $this->api->getCloud();
        $this->validateCloud($cloud);
    }

    public function testSetCloud()
    {
        $id = md5(uniqid());
        $data = array(
            'name' => 'my_first_cloud',
            's3_videos_bucket' => 'my_own_bucket',
            'aws_access_key' => 'XQwEwFR',
            'aws_secret_key' => 'XoSV2f'
        );
        $this->request('put', '/clouds/'.$id.'.json', $this->createCloudResponse(), $data);
        $cloud = $this->api->setCloud($data, $id);
        $this->validateCloud($cloud);
    }

    public function testSetCloudWithoutId()
    {
        $data = array(
            'name' => 'my_first_cloud',
            's3_videos_bucket' => 'my_own_bucket',
            'aws_access_key' => 'XQwEwFR',
            'aws_secret_key' => 'XoSV2f'
        );
        $this->request(
            'put',
            '/clouds/'.$this->restClient->getCloudId().'.json',
            $this->createCloudResponse(),
            $data
        );
        $cloud = $this->api->setCloud($data);
        $this->validateCloud($cloud);
    }

    public function testGetNotifications()
    {
        $this->request(
            'get',
            '/notifications.json',
            $this->createNotificationsResponse(null, false, false, false, false)
        );
        $notifications = $this->api->getNotifications();
        $this->validateNotifications($notifications, null, false, false, false, false);
    }

    public function testSetNotifications()
    {
        // the new notifications configuration
        $notifications = new Notifications();
        $notifications->setUrl('http://example.com/panda_notification');
        $notifications->addNotificationEvent(
            new NotificationEvent('video-created', false)
        );
        $notifications->addNotificationEvent(
            new NotificationEvent('video-encoded', true)
        );
        $notifications->addNotificationEvent(
            new NotificationEvent('encoding-progress', false)
        );
        $notifications->addNotificationEvent(
            new NotificationEvent('encoding-completed', false)
        );

        // what will be passed to the underlying api call
        $url = 'http://example.com/panda_notification';
        $data = array(
            'url' => $url,
            'events[video_created]' => 'false',
            'events[video_encoded]' => 'true',
            'events[encoding_progress]' => 'false',
            'events[encoding_completed]' => 'false'
        );

        $this->request(
            'put',
            '/notifications.json',
            $this->createNotificationsResponse($url, false, true, false, true),
            $data
        );
        $notifications = $this->api->setNotifications($data);
        $this->validateNotifications($notifications, $url, false, true, false, true);
    }

    private function createRestClient()
    {
        $this->restClient = $this->getMock(
            'Xabbuh\PandaClient\Api\RestClientInterface'
        );
        $this->restClient
            ->expects($this->any())
            ->method('getCloudId')
            ->will($this->returnValue(md5(uniqid())))
        ;
    }

    private function createTransformers()
    {
        $this->transformers = new TransformerRegistry();
        $this->transformers->setCloudTransformer(new CloudTransformer());
        $this->transformers->setEncodingTransformer(new EncodingTransformer());
        $this->transformers->setNotificationsTransformer(new NotificationsTransformer());
        $this->transformers->setProfileTransformer(new ProfileTransformer());
        $this->transformers->setVideoTransformer(new VideoTransformer());
    }

    private function createVideoResponse()
    {
        return '{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "original_filename":"video.mp4",
          "extname":".mp4",
          "path":"d891d9a45c698d587831466f236c6c6c",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":240,
          "width":300,
          "fps":29,
          "duration":14000,
          "file_size":39458349,
          "created_at":"2009/10/13 19:11:26 +0100",
          "updated_at":"2009/10/13 19:11:26 +0100"
        }';
    }

    private function createVideosResponse()
    {
        return '[{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "original_filename":"test.mp4",
          "extname":".mp4",
          "path":"d891d9a45c698d587831466f236c6c6c",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":240,
          "width":300,
          "fps":29,
          "duration":14000,
          "file_size": 39458349,
          "created_at":"2009/10/13 19:11:26 +0100",
          "updated_at":"2009/10/13 19:11:26 +0100"
        },
        {
          "id":"130466751aaaac1f88eb7e31c93ce40c",
          "source_url": "http://example.com/test2.mp4",
          "extname":".mp4",
          "path":"130466751aaaac1f88eb7e31c93ce40c",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":640,
          "width":360
        }]';
    }

    private function createVideosForPaginationResponse($page = 1, $perPage = 100)
    {
        return '{ "videos": [{
              "id":"d891d9a45c698d587831466f236c6c6c",
              "original_filename":"test.mp4",
              "extname":".mp4",
              "path":"d891d9a45c698d587831466f236c6c6c",
              "video_codec":"h264",
              "audio_codec":"aac",
              "height":240,
              "width":300,
              "fps":29,
              "duration":14000,
              "file_size": 39458349,
              "created_at":"2009/10/13 19:11:26 +0100",
              "updated_at":"2009/10/13 19:11:26 +0100"
            },
            {
              "id":"130466751aaaac1f88eb7e31c93ce40c",
              "source_url": "http://example.com/test2.mp4",
              "extname":".mp4",
              "path":"130466751aaaac1f88eb7e31c93ce40c",
              "video_codec":"h264",
              "audio_codec":"aac",
              "height":640,
              "width":360
            }],
        "page": '.$page.',
        "per_page": '.$perPage.',
        "total": 17
        }';
    }

    private function createEncodingResponse($encodingId, $videoId, $profileId)
    {
        return '{
          "id":"'.$encodingId.'",
          "video_id":"' . $videoId . '",
          "extname":".mp4",
          "path":"'.$encodingId.'",
          "profile_id":"' . $profileId . '",
          "profile_name":"h264",
          "status":"processing",
          "encoding_progress":0,
          "height":240,
          "width":300,
          "started_encoding_at":"",
          "encoding_time":0,
          "files":[],
          "created_at":"2009/10/13 20:58:29 +0000",
          "updated_at":"2009/10/13 21:30:34 +0000"
        }';
    }

    private function createEncodingsResponse()
    {
        return '[{
          "id":"2f8760b7e0d4c7dbe609b5872be9bc3b",
          "video_id":"d891d9a45c698d587831466f236c6c6c",
          "extname":".mp4",
          "path":"2f8760b7e0d4c7dbe609b5872be9bc3b",
          "profile_id":"40d9f8711d64aaa74f88462e9274f39a",
          "profile_name":"h264",
          "status":"success",
          "encoding_progress":99,
          "height":240,
          "width":300,
          "started_encoding_at":"2009/10/13 21:28:45 +0000",
          "encoding_time":9000,
          "files":["2f8760b7e0d4c7dbe609b5872be9bc3b.mp4"],
          "created_at":"2009/10/13 20:58:29 +0000",
          "updated_at":"2009/10/13 21:30:34 +0000"
        },
        {
          "id":"ab658d9599ca70966cfd0f53c186712b",
          "video_id":"b7e67ca5c92f381f7fd9ce341e6609c6",
          "extname":".mp4",
          "path":"ab658d9599ca70966cfd0f53c186712b",
          "profile_id":"ab658d9599ca70966cfd0f53c186712b",
          "profile_name":"h264",
          "status":"success",
          "encoding_progress":50,
          "height":240,
          "width":300,
          "files":["ab658d9599ca70966cfd0f53c186712b.mp4"],
          "created_at":"2011/1/31 10:39:13 +0000",
          "updated_at":"2012/12/5 01:49:27 +0000"
        }]';
    }

    private function createProfileResponse()
    {
        return '{
           "id":"40d9f8711d64aaa74f88462e9274f39a",
           "title":"H264 (MP4)",
           "name": "h264",
           "extname":".mp4",
           "width":320,
           "height":240,
           "audio_bitrate": 128,
           "video_bitrate": 500,
           "aspect_mode": "letterbox",
           "command":"ffmpeg -i $input_file$ -c:a libfaac $audio_bitrate$ -c:v libx264 $video_bitrate$ -preset medium $filters$ -y $output_file$",
           "created_at":"2009/10/14 18:36:30 +0000",
           "updated_at":"2009/10/14 19:38:42 +0000"
        }';
    }

    private function createCloudResponse()
    {
        return '{
          "id": "e122090f4e506ae9ee266c3eb78a8b67",
          "name": "my_first_cloud",
          "s3_videos_bucket": "my-example-bucket",
          "s3_private_access":false,
          "url": "http://my-example-bucket.s3.amazonaws.com/",
          "created_at": "2010/03/18 12:56:04 +0000",
          "updated_at": "2010/03/18 12:59:06 +0000"
        }';
    }

    private function createNotificationsResponse(
        $url,
        $videoCreatedEventActive,
        $videoEncodedEventActive,
        $encodingProgressEventActive,
        $encodingCompletedEventActive
    ) {
        return '{
          "url": '.(null === $url ? 'null' : '"'.$url.'"').',
          "events": {
            "video_created": '.($videoCreatedEventActive ? 'true' : 'false').',
            "video_encoded": '.($videoEncodedEventActive ? 'true' : 'false').',
            "encoding_progress": '.($encodingProgressEventActive ? 'true' : 'false').',
            "encoding_completed": '.($encodingCompletedEventActive ? 'true' : 'false').'
          }
        }';
    }

    private function request($method, $resource, $response, $params = null)
    {
        if (null !== $params) {
            $this->restClient
                ->expects($this->once())
                ->method($method)
                ->with(
                    $this->equalTo($resource),
                    is_array($params) ? $this->equalTo($params) : $params
                )
                ->will($this->returnValue($response))
            ;
        } else {
            $this->restClient
                ->expects($this->once())
                ->method($method)
                ->with($this->equalTo($resource))
                ->will($this->returnValue($response))
            ;
        }
    }

    private function requestVideosForPagination($page, $perPage)
    {
        $this->request(
            'get',
            '/videos.json',
            $this->createVideosForPaginationResponse($page, $perPage),
            array(
                'include_root' => true,
                'page' => $page,
                'per_page' => $perPage,
            )
        );
    }

    /**
     * @param Video $video
     */
    private function validateVideo($video)
    {
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Video', $video);
        $this->assertEquals('d891d9a45c698d587831466f236c6c6c', $video->getId());
        $this->assertEquals('video.mp4', $video->getOriginalFilename());
        $this->assertEquals('.mp4', $video->getExtname());
        $this->assertEquals('d891d9a45c698d587831466f236c6c6c', $video->getPath());
        $this->assertEquals('h264', $video->getVideoCodec());
        $this->assertEquals('aac', $video->getAudioCodec());
        $this->assertEquals('240', $video->getHeight());
        $this->assertEquals('300', $video->getWidth());
        $this->assertEquals('29', $video->getFps());
        $this->assertEquals('14000', $video->getDuration());
        $this->assertEquals('39458349', $video->getFileSize());
        $this->assertEquals('2009/10/13 19:11:26 +0100', $video->getCreatedAt());
        $this->assertEquals('2009/10/13 19:11:26 +0100', $video->getUpdatedAt());
    }

    private function validateCollection(
        $collection,
        $class,
        $size = -1,
        array $ids = array()
    ) {
        $this->assertTrue(is_array($collection) || $collection instanceof \Countable);

        if (0 <= $size) {
            $this->assertEquals($size, count($collection));
        }

        foreach ($collection as $key => $object) {
            $this->assertInstanceOf($class, $object);

            if (isset($ids[$key])) {
                $this->assertEquals($ids[$key], $object->getId());
            }
        }
    }

    private function validateVideosForPagination($result, $page, $perPage)
    {
        $this->assertEquals($page, $result->page);
        $this->assertEquals($perPage, $result->per_page);
        $this->assertEquals(17, $result->total);
        $this->validateCollection($result->videos, 'Xabbuh\PandaClient\Model\Video', 2);
    }

    /**
     * @param CloudModel $cloud
     */
    private function validateCloud($cloud)
    {
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Cloud', $cloud);
        $this->assertEquals('e122090f4e506ae9ee266c3eb78a8b67', $cloud->getId());
        $this->assertEquals('my_first_cloud', $cloud->getName());
        $this->assertEquals('my-example-bucket', $cloud->getS3VideosBucket());
        $this->assertEquals(false, $cloud->isS3AccessPrivate());
        $this->assertEquals('http://my-example-bucket.s3.amazonaws.com/', $cloud->getUrl());
        $this->assertEquals('2010/03/18 12:56:04 +0000', $cloud->getCreatedAt());
        $this->assertEquals('2010/03/18 12:59:06 +0000', $cloud->getUpdatedAt());
    }

    /**
     * @param Notifications $notifications
     * @param string        $url
     * @param boolean       $videoCreatedEventActive
     * @param boolean       $videoEncodedEventActive
     * @param boolean       $encodingProgressEventActive
     * @param boolean       $encodingCompletedEventActive
     */
    private function validateNotifications(
        $notifications,
        $url,
        $videoCreatedEventActive,
        $videoEncodedEventActive,
        $encodingProgressEventActive,
        $encodingCompletedEventActive
    ) {
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Notifications', $notifications);

        if (null === $url) {
            $this->assertNull($notifications->getUrl());
        } else {
            $this->assertEquals($url, $notifications->getUrl());
        }

        $videoCreatedEvent = $notifications->getNotificationEvent('video-created');
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            $videoCreatedEvent
        );
        $this->assertEquals($videoCreatedEventActive, $videoCreatedEvent->isActive());

        $videoEncodedEvent = $notifications->getNotificationEvent('video-encoded');
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            $videoEncodedEvent
        );
        $this->assertEquals($videoEncodedEventActive, $videoEncodedEvent->isActive());

        $encodingProgressEvent = $notifications->getNotificationEvent('encoding-progress');
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            $encodingProgressEvent
        );
        $this->assertEquals($encodingProgressEventActive, $encodingProgressEvent->isActive());

        $encodingCompletedEvent = $notifications->getNotificationEvent('encoding-completed');
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            $encodingCompletedEvent
        );
        $this->assertEquals($encodingCompletedEventActive, $encodingCompletedEvent->isActive());
    }
}
