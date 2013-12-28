<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Transformer;

use Xabbuh\PandaClient\Transformer\ProfileTransformer;

/**
 * Tests for the ProfileTransformer class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ProfileTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The Profile transformer
     * @var ProfileTransformer
     */
    private static $transformer;


    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        self::$transformer = new ProfileTransformer();
    }

    /**
     * Test the transformation of a JSON encoded string into a Profile object.
     */
    public function testFromJSON()
    {
        $jsonString = '{
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
        }';
        $profile = self::$transformer->fromJSON($jsonString);
        $this->assertEquals(
            "Xabbuh\PandaClient\Model\Profile",
            get_class($profile)
        );
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
     * Test that a non-existing property throws an exception.
     */
    public function testFromJSONWithInvalidProfile()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Transformation error: Unknown property profile_name in model'
        );
        $jsonString = '{
          "id":"40d9f8711d64aaa74f88462e9274f39a",
          "profile_name":"h264"
        }';
        self::$transformer->fromJson($jsonString);
    }

    /**
     * Test the transformation of a JSON encoded collection of profiles.
     */
    public function testFromJSONCollection()
    {
        $jsonString = '[{
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
        $profiles = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($profiles));
        $this->assertEquals(2, count($profiles));
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Profile',
            get_class($profiles[0])
        );
        $this->assertEquals(
            '40d9f8711d64aaa74f88462e9274f39a',
            $profiles[0]->getId()
        );
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Profile',
            get_class($profiles[1])
        );
        $this->assertEquals(
            'c629221e4595928f7f6838bfd30469c3',
            $profiles[1]->getId()
        );
    }

    /**
     * Test the transformation of an empty JSON encoded collection of profiles.
     */
    public function testFromJSONCollectionWithEmptyCollection()
    {
        $jsonString = '[]';
        $collection = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($collection));
        $this->assertEquals(0, count($collection));
    }
}
