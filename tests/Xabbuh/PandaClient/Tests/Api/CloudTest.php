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
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Transformer\TransformerRegistryInterface;

/**
 * Tests for the api implementation.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RestClientInterface
     */
    private $restClient;

    /**
     * @var TransformerRegistryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $transformerRegistry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject[]
     */
    private $transformers;

    /**
     * @var Cloud
     */
    private $cloud;

    protected function setUp()
    {
        $this->createRestClient();
        $this->createTransformers();
        $this->cloud = new Cloud($this->restClient, $this->transformerRegistry);
    }

    public function testGetRestClient()
    {
        $this->assertEquals($this->restClient, $this->cloud->getRestClient());
    }

    public function testGetVideos()
    {
        $this->validateRequest('get', '/videos.json');
        $this->validateTransformer('Video', 'fromJSONCollection');

        $this->cloud->getVideos();
    }

    public function testGetVideosForPaginationWithDefaultParameters()
    {
        $this->validateRequest(
            'get',
            '/videos.json',
            array('include_root' => true, 'page' => 1, 'per_page' => 100),
            '{ "videos": [] }'
        );
        $this->validateTransformer('Video', 'fromJSONCollection');

        $this->cloud->getVideosForPagination();
    }

    public function testGetVideosForPaginationWithPageParameter()
    {
        $this->validateRequest(
            'get',
            '/videos.json',
            array('include_root' => true, 'page' => 5, 'per_page' => 100),
            '{ "videos": [] }'
        );
        $this->validateTransformer('Video', 'fromJSONCollection');

        $this->cloud->getVideosForPagination(5);
    }

    public function testGetVideosForPaginationWithPageAndPerPageParameters()
    {
        $this->validateRequest(
            'get',
            '/videos.json',
            array('include_root' => true, 'page' => 7, 'per_page' => 25),
            '{ "videos": [] }'
        );
        $this->validateTransformer('Video', 'fromJSONCollection');

        $this->cloud->getVideosForPagination(7, 25);
    }

    public function testGetVideo()
    {
        $videoId = md5(uniqid());
        $this->validateRequest('get', '/videos/'.$videoId.'.json');
        $this->validateTransformer('Video', 'fromJSON');

        $this->cloud->getVideo($videoId);
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
        $this->validateRequest(
            'get',
            '/videos/'.$videoId.'/metadata.json',
            null,
            $response);
        $metadata = $this->cloud->getVideoMetadata($videoId);
        $this->assertTrue(is_array($metadata));
        $this->assertEquals(208, $metadata['image_height']);
        $this->assertEquals('mp4a', $metadata['audio_format']);
        $this->assertEquals('0 s', $metadata['selection_time']);
    }

    public function testDeleteVideo()
    {
        $videoId = md5(uniqid());
        $this->validateRequest('delete', '/videos/'.$videoId.'.json');
        $this->cloud->deleteVideo($videoId);
    }

    public function testEncodeVideoByUrl()
    {
        $url = 'http://www.example.com/video.mp4';
        $this->validateRequest('post', '/videos.json', array('source_url' => $url));
        $this->validateTransformer('Video', 'fromJSON');

        $this->cloud->encodeVideoByUrl($url);
    }

    public function testEncodeVideoFile()
    {
        $filename = 'video.mp4';
        $this->validateRequest('post', '/videos.json', array('file' => '@'.$filename));
        $this->validateTransformer('Video', 'fromJSON');

        $this->cloud->encodeVideoFile($filename);
    }

    public function testRegisterUpload()
    {
        $id = md5(uniqid());
        $location = 'http://example.com/'.$id;
        $response = sprintf('{ "id": "%s", "location": "%s" }', $id, $location);
        $options = array(
            'file_name' => 'video.mp4',
            'file_size' => 114373213,
            'use_all_profiles' => false,
        );
        $this->validateRequest('post', '/videos/upload.json', $options, $response);

        $upload = $this->cloud->registerUpload('video.mp4', 114373213);

        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testRegisterUploadWithProfilesList()
    {
        $id = md5(uniqid());
        $location = 'http://example.com/'.$id;
        $response = sprintf('{ "id": "%s", "location": "%s" }', $id, $location);
        $profiles = array('profile1', 'profile2');
        $options = array(
            'file_name' => 'video.mp4',
            'file_size' => 114373213,
            'profiles' => implode(',', $profiles),
        );
        $this->validateRequest('post', '/videos/upload.json', $options, $response);

        $upload = $this->cloud->registerUpload('video.mp4', 114373213, $profiles);

        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testRegisterUploadWithAllProfiles()
    {
        $id = md5(uniqid());
        $location = 'http://example.com/'.$id;
        $response = sprintf('{ "id": "%s", "location": "%s" }', $id, $location);
        $options = array(
            'file_name' => 'video.mp4',
            'file_size' => 114373213,
            'use_all_profiles' => true,
        );
        $this->validateRequest('post', '/videos/upload.json', $options, $response);

        $upload = $this->cloud->registerUpload('video.mp4', 114373213, null, true);

        $this->assertEquals($id, $upload->id);
        $this->assertEquals($location, $upload->location);
    }

    public function testGetEncodings()
    {
        $this->validateRequest('get', '/encodings.json');
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodings();
    }

    public function testGetEncodingsWithFilter()
    {
        $this->validateRequest('get', '/encodings.json', array('status' => 'success'));
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodings(array('status' => 'success'));
    }

    public function testGetEncodingsWithStatus()
    {
        $this->validateRequest('get', '/encodings.json', array('status' => 'success'));
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsWithStatus('success');
    }

    public function testGetEncodingsWithStatusAndFilter()
    {
        $videoId = md5(uniqid());
        $this->validateRequest(
            'get',
            '/encodings.json',
            array('status' => 'success', 'video_id' => $videoId)
        );
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsWithStatus('success', array('video_id' => $videoId));
    }

    public function testGetEncodingsForProfile()
    {
        $profileId = md5(uniqid());
        $this->validateRequest('get', '/encodings.json', array('profile_id' => $profileId));
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForProfile($profileId);
    }

    public function testGetEncodingsForProfileWithFilter()
    {
        $profileId = md5(uniqid());
        $this->validateRequest(
            'get',
            '/encodings.json',
            array('profile_id' => $profileId, 'status' => 'success')
        );
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForProfile($profileId, array('status' => 'success'));
    }

    public function testGetEncodingsForProfileByName()
    {
        $this->validateRequest('get', '/encodings.json', array('profile_name' => 'h264'));
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForProfileByName('h264');
    }

    public function testGetEncodingsForProfileByNameWithFilter()
    {
        $this->validateRequest(
            'get',
            '/encodings.json',
            array('profile_name' => 'h264', 'status' => 'success')
        );
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForProfileByName('h264', array('status' => 'success'));
    }

    public function testGetEncodingsForVideo()
    {
        $videoId = md5(uniqid());
        $this->validateRequest('get', '/encodings.json', array('video_id' => $videoId));
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForVideo($videoId);
    }

    public function testGetEncodingsForVideoWithFilter()
    {
        $videoId = md5(uniqid());
        $this->validateRequest(
            'get',
            '/encodings.json',
            array('video_id' => $videoId, 'status' => 'success')
        );
        $this->validateTransformer('Encoding', 'fromJSONCollection');

        $this->cloud->getEncodingsForVideo($videoId, array('status' => 'success'));
    }

    public function testGetEncoding()
    {
        $encodingId = md5(uniqid());
        $this->validateRequest('get', '/encodings/'.$encodingId.'.json');
        $this->validateTransformer('Encoding', 'fromJSON');

        $this->cloud->getEncoding($encodingId);
    }

    public function testCreateEncoding()
    {
        $videoId = md5(uniqid());
        $profileId = md5(uniqid());
        $this->validateRequest(
            'post',
            '/encodings.json',
            array( 'video_id' => $videoId, 'profile_id' => $profileId)
        );
        $this->validateTransformer('Encoding', 'fromJSON');

        $this->cloud->createEncoding($videoId, $profileId);
    }

    public function testCreateEncodingWithProfileName()
    {
        $videoId = md5(uniqid());
        $profileName = 'h264';
        $this->validateRequest(
            'post',
            '/encodings.json',
            array( 'video_id' => $videoId, 'profile_name' => $profileName)
        );
        $this->validateTransformer('Encoding', 'fromJSON');

        $this->cloud->createEncodingWithProfileName($videoId, $profileName);
    }

    public function testCancelEncoding()
    {
        $encodingId = md5(uniqid());
        $this->validateRequest('post', '/encodings/'.$encodingId.'/cancel.json');
        $this->cloud->cancelEncoding($encodingId);
    }

    public function testRetryEncoding()
    {
        $encodingId = md5(uniqid());
        $this->validateRequest('post', '/encodings/'.$encodingId.'/retry.json');
        $this->cloud->retryEncoding($encodingId);
    }

    public function testDeleteEncoding()
    {
        $encodingId = md5(uniqid());
        $this->validateRequest('delete', '/encodings/'.$encodingId.'.json');
        $this->cloud->deleteEncoding($encodingId);
    }

    public function testGetProfiles()
    {
        $this->validateRequest('get', '/profiles.json');
        $this->validateTransformer('Profile', 'fromJSONCollection');

        $this->cloud->getProfiles();
    }

    public function testGetProfile()
    {
        $profileId = md5(uniqid());
        $this->validateRequest('get', '/profiles/'.$profileId.'.json');
        $this->validateTransformer('Profile', 'fromJSON');

        $this->cloud->getProfile($profileId);
    }

    public function testAddProfile()
    {
        $this->validateRequest('post', '/profiles.json');
        $this->validateTransformer('Profile', 'fromJSON');

        $this->cloud->addProfile(array());
    }

    public function testAddProfileFromPreset()
    {
        $this->validateRequest('post', '/profiles.json', array('preset_name' => 'h264'));
        $this->validateTransformer('Profile', 'fromJSON');

        $this->cloud->addProfileFromPreset('h264');
    }

    public function testSetProfile()
    {
        $parameterBag = $this->getMock('Symfony\Component\HttpFoundation\ParameterBag');
        $parameterBag->expects($this->once())
            ->method('all')
            ->will($this->returnValue(array()));

        $profileId = md5(uniqid());
        $profile = new Profile();
        $profile->setId($profileId);
        $this->validateRequest('put', '/profiles/'.$profileId.'.json');
        $this->validateTransformer('Profile', 'toRequestParams', $parameterBag);

        $this->cloud->setProfile($profile);
    }

    public function testDeleteProfile()
    {
        $profileId = md5(uniqid());
        $profile = new Profile();
        $profile->setId($profileId);
        $this->validateRequest('delete', '/profiles/'.$profileId.'.json');
        $this->cloud->deleteProfile($profile);
    }

    public function testGetCloud()
    {
        $cloudId = md5(uniqid());
        $this->validateRequest('get', '/clouds/'.$cloudId.'.json');
        $this->validateTransformer('Cloud', 'fromJSON');

        $this->cloud->getCloud($cloudId);
    }

    public function testGetCloudWithoutId()
    {
        $this->validateRequest('get', '/clouds/'.$this->restClient->getCloudId().'.json');
        $this->validateTransformer('Cloud', 'fromJSON');

        $this->cloud->getCloud();
    }

    public function testSetCloud()
    {
        $cloudId = md5(uniqid());
        $data = array(
            'name' => 'my_first_cloud',
            's3_videos_bucket' => 'my_own_bucket',
            'aws_access_key' => 'XQwEwFR',
            'aws_secret_key' => 'XoSV2f'
        );
        $this->validateRequest('put', '/clouds/'.$cloudId.'.json', $data);
        $this->validateTransformer('Cloud', 'fromJSON');

        $this->cloud->setCloud($data, $cloudId);
    }

    public function testSetCloudWithoutId()
    {
        $data = array(
            'name' => 'my_first_cloud',
            's3_videos_bucket' => 'my_own_bucket',
            'aws_access_key' => 'XQwEwFR',
            'aws_secret_key' => 'XoSV2f'
        );
        $this->validateRequest(
            'put',
            '/clouds/'.$this->restClient->getCloudId().'.json',
            $data
        );
        $this->validateTransformer('Cloud', 'fromJSON');

        $this->cloud->setCloud($data);
    }

    public function testGetNotifications()
    {
        $this->validateRequest('get', '/notifications.json');
        $this->validateTransformer('Notifications', 'fromJSON');

        $this->cloud->getNotifications();
    }

    public function testSetNotifications()
    {
        $data = array(
            'url' => 'http://example.com/panda_notification',
            'events[video_created]' => 'false',
            'events[video_encoded]' => 'true',
            'events[encoding_progress]' => 'false',
            'events[encoding_completed]' => 'false'
        );
        $this->validateRequest('put', '/notifications.json', $data);
        $this->validateTransformer('Notifications', 'fromJSON');

        $this->cloud->setNotifications($data);
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
        $this->transformerRegistry = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\TransformerRegistryInterface'
        );

        $cloudTransformer = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\CloudTransformerInterface'
        );
        $this->transformerRegistry
            ->expects($this->any())
            ->method('getCloudTransformer')
            ->will($this->returnValue($cloudTransformer));
        $this->transformers['Cloud'] = $cloudTransformer;

        $encodingTransformer = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\EncodingTransformerInterface'
        );
        $this->transformerRegistry
            ->expects($this->any())
            ->method('getEncodingTransformer')
            ->will($this->returnValue($encodingTransformer));
        $this->transformers['Encoding'] = $encodingTransformer;

        $notificationsTransformer = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\NotificationsTransformerInterface'
        );
        $this->transformerRegistry
            ->expects($this->any())
            ->method('getNotificationsTransformer')
            ->will($this->returnValue($notificationsTransformer));
        $this->transformers['Notifications'] = $notificationsTransformer;

        $profileTransformer = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\ProfileTransformer'
        );
        $this->transformerRegistry
            ->expects($this->any())
            ->method('getProfileTransformer')
            ->will($this->returnValue($profileTransformer));
        $this->transformers['Profile'] = $profileTransformer;

        $videoTransformer = $this->getMock(
            '\Xabbuh\PandaClient\Transformer\VideoTransformerInterface'
        );
        $this->transformerRegistry
            ->expects($this->any())
            ->method('getVideoTransformer')
            ->will($this->returnValue($videoTransformer));
        $this->transformers['Video'] = $videoTransformer;
    }

    private function validateRequest($method, $resource, $params = null, $response = null)
    {
        if (null !== $params) {
            $client = $this->restClient
                ->expects($this->once())
                ->method($method)
                ->with(
                    $this->equalTo($resource),
                    is_array($params) ? $this->equalTo($params) : $params
                );
        } else {
            $client = $this->restClient
                ->expects($this->once())
                ->method($method)
                ->with($this->equalTo($resource));
        }

        if (null !== $response) {
            $client->will($this->returnValue($response));
        }
    }

    private function validateTransformer($model, $method, $returnValue = null)
    {
        $this->transformerRegistry
            ->expects($this->once())
            ->method('get'.$model.'Transformer');

        $transformer = $this->transformers[$model];
        $transformer = $transformer->expects($this->once())
            ->method($method);

        if (null !== $returnValue) {
            $transformer->will($this->returnValue($returnValue));
        }
    }
}
