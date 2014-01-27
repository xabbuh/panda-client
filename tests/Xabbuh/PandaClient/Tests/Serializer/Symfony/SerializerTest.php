<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Serializer\Symfony;

use Xabbuh\PandaClient\Model\Cloud;
use Xabbuh\PandaClient\Model\Encoding;
use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Model\Profile;
use Xabbuh\PandaClient\Model\Video;
use Xabbuh\PandaClient\Serializer\Symfony\Serializer;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $cloudSerializer;

    /**
     * @var Serializer
     */
    private $encodingSerializer;

    /**
     * @var Serializer
     */
    private $profileSerializer;

    /**
     * @var Serializer
     */
    private $videoSerializer;

    protected function setUp()
    {
        $this->cloudSerializer = new Serializer();
        $this->encodingSerializer = new Serializer();
        $this->profileSerializer = new Serializer();
        $this->videoSerializer = new Serializer();
    }

    public function testCloud()
    {
        $data = '{
          "id": "e122090f4e506ae9ee266c3eb78a8b67",
          "name": "my_first_cloud",
          "s3_videos_bucket": "my-example-bucket",
          "s3_private_access":false,
          "url": "http://my-example-bucket.s3.amazonaws.com/",
          "created_at": "2010/03/18 12:56:04 +0000",
          "updated_at": "2010/03/18 12:59:06 +0000"
        }';
        $cloud = $this->cloudSerializer->deserialize($data, 'Cloud');

        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Cloud', $cloud);
        $this->assertEquals('e122090f4e506ae9ee266c3eb78a8b67', $cloud->getId());
        $this->assertEquals('my_first_cloud', $cloud->getName());
        $this->assertEquals('my-example-bucket', $cloud->getS3VideosBucket());
        $this->assertEquals(false, $cloud->isS3AccessPrivate());
        $this->assertEquals('http://my-example-bucket.s3.amazonaws.com/', $cloud->getUrl());
        $this->assertEquals('2010/03/18 12:56:04 +0000', $cloud->getCreatedAt());
        $this->assertEquals('2010/03/18 12:59:06 +0000', $cloud->getUpdatedAt());
    }

    public function testEncoding()
    {
        $encodingId = md5(uniqid());
        $videoId = md5(uniqid());
        $profileId = md5(uniqid());
        $data = '{
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
        $encoding = $this->encodingSerializer->deserialize($data, 'Encoding');

        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Encoding', $encoding);
        $this->assertEquals($encodingId, $encoding->getId());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Denormalization error: Unknown attribute videosid in model
     */
    public function testEncodingWithUnknownProperty()
    {
        $data = '{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "videosid":"d891d9a45c698d587831466f236c6c6c"
        }';
        $this->encodingSerializer->deserialize($data, 'Encoding');
    }

    public function testEncodings()
    {
        $data = '[{
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
        $encodings = $this->encodingSerializer->deserialize($data, 'Encoding');

        $this->validateCollection(
            $encodings,
            'Encoding',
            2,
            array('2f8760b7e0d4c7dbe609b5872be9bc3b', 'ab658d9599ca70966cfd0f53c186712b')
        );
    }

    public function testEncodingsWithEmptyCollection()
    {
        $data = '[]';
        $collection = $this->encodingSerializer->deserialize($data, 'Video');

        $this->validateCollection($collection, 'Encoding', 0);
    }

    public function testProfile()
    {
        $data = '{
           "id":"40d9f8711d64aaa74f88462e9274f39a",
           "title":"MP4 (H.264)",
           "name": "h264",
           "extname":".mp4",
           "audio_sample_rate":"44100",
           "width":320,
           "height":240,
           "audio_bitrate": 128,
           "video_bitrate": 500,
           "keyframe_interval":250,
           "keyframe_rate":24,
           "aspect_mode": "letterbox",
           "preset_name":"webm.hi",
           "audio_channels":2,
           "clip_length":"00:20:00",
           "clip_offset":"00:00:10",
           "frame_count":7,
           "command":"ffmpeg -i $input_file$ -c:a libfaac $audio_bitrate$ -c:v libx264 $video_bitrate$ -preset medium $filters$ -y $output_file$",
           "created_at":"2009/10/14 18:36:30 +0000",
           "updated_at":"2009/10/14 19:38:42 +0000"
        }';
        $profile = $this->profileSerializer->deserialize($data, 'Profile');

        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Profile', $profile);
        $this->assertEquals('40d9f8711d64aaa74f88462e9274f39a', $profile->getId());
        $this->assertEquals('MP4 (H.264)', $profile->getTitle());
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

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Denormalization error: Unknown attribute profile_name in model
     */
    public function testProfileWithUnknownProperty()
    {
        $data = '{
          "id":"40d9f8711d64aaa74f88462e9274f39a",
          "profile_name":"h264"
        }';
        $this->profileSerializer->deserialize($data, 'Profile');
    }

    public function testProfiles()
    {
        $data = '[{
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
        $collection = $this->profileSerializer->deserialize($data, 'Profile');

        $this->validateCollection(
            $collection,
            'Profile',
            2,
            array('40d9f8711d64aaa74f88462e9274f39a', 'c629221e4595928f7f6838bfd30469c3')
        );
    }

    public function testProfilesWithEmptyCollection()
    {
        $data = '[]';
        $collection = $this->profileSerializer->deserialize($data, 'Profile');

        $this->validateCollection($collection, 'Profile', 0);
    }

    public function testVideo()
    {
        $data = '{
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
          "file_size":39458349,
          "created_at":"2009/10/13 19:11:26 +0100",
          "updated_at":"2009/10/13 19:11:26 +0100"
        }';
        $video = $this->videoSerializer->deserialize($data, 'Video');

        $this->assertEquals('d891d9a45c698d587831466f236c6c6c', $video->getId());
        $this->assertEquals('test.mp4', $video->getOriginalFilename());
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

    public function testVideoWithSourceUrl()
    {
        $data = '{
          "id":"130466751aaaac1f88eb7e31c93ce40c",
          "source_url": "http://example.com/test2.mp4",
          "extname":".mp4",
          "path":"130466751aaaac1f88eb7e31c93ce40c",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":360,
          "width":640
        }';
        $video = $this->videoSerializer->deserialize($data, 'Video');

        $this->assertEquals('130466751aaaac1f88eb7e31c93ce40c', $video->getId());
        $this->assertEquals('http://example.com/test2.mp4', $video->getSourceUrl());
        $this->assertEquals('.mp4', $video->getExtname());
        $this->assertEquals('130466751aaaac1f88eb7e31c93ce40c', $video->getPath());
        $this->assertEquals('h264', $video->getVideoCodec());
        $this->assertEquals('aac', $video->getAudioCodec());
        $this->assertEquals('640', $video->getWidth());
        $this->assertEquals('360', $video->getHeight());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Denormalization error: Unknown attribute source_file in model
     */
    public function testVideoWithUnknownProperty()
    {
        $data = '{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "source_file":"test.mp4"
        }';
        $this->videoSerializer->deserialize($data, 'Video');
    }

    public function testVideos()
    {
        $data = '[{
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
        $collection = $this->videoSerializer->deserialize($data, 'Video');

        $this->validateCollection($collection, 'Video', 2);
    }

    public function testVideosWithEmptyCollection()
    {
        $data = '[]';
        $collection = $this->videoSerializer->deserialize($data, 'Video');

        $this->validateCollection($collection, 'Video', 0);
    }

    public function testGetCloudSerializer()
    {
        $this->assertEquals($this->cloudSerializer, Serializer::getCloudSerializer());
    }

    public function testGetEncodingSerializer()
    {
        $this->assertEquals($this->encodingSerializer, Serializer::getEncodingSerializer());
    }

    public function testGetProfileSerializer()
    {
        $this->assertEquals($this->profileSerializer, Serializer::getProfileSerializer());
    }

    public function testGetVideoSerializer()
    {
        $this->assertEquals($this->videoSerializer, Serializer::getVideoSerializer());
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
            /** @var Cloud|Encoding|Notifications|Profile|Video $object */

            $this->assertInstanceOf('Xabbuh\PandaClient\Model\\'.$class, $object);

            if (isset($ids[$key])) {
                $this->assertEquals($ids[$key], $object->getId());
            }
        }
    }
}
