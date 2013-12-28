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

use Xabbuh\PandaClient\Transformer\VideoTransformer;

/**
 * Test the {@link \Xabbuh\PandaClient\Transformer\VideoTransformer VideoTransformer}
 * class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class VideoTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VideoTransformer
     */
    private static $transformer;


    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        self::$transformer = new VideoTransformer();
    }

    public function testFromJSON()
    {
        $jsonString = '{
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
        $video = self::$transformer->fromJson($jsonString);
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

        $jsonString = '{
          "id":"130466751aaaac1f88eb7e31c93ce40c",
          "source_url": "http://example.com/test2.mp4",
          "extname":".mp4",
          "path":"130466751aaaac1f88eb7e31c93ce40c",
          "video_codec":"h264",
          "audio_codec":"aac",
          "height":360,
          "width":640
        }';
        $video = self::$transformer->fromJSON($jsonString);
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
     * Test that a non-existing property throws an exception.
     */
    public function testFromJSONWithInvalidVideo()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Transformation error: Unknown property source_file in model'
        );
        $jsonString = '{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "source_file":"test.mp4"
        }';
        self::$transformer->fromJson($jsonString);
    }

    /**
     * Test the transformation of a JSON encoded collection of videos.
     */
    public function testFromJSONCollection()
    {
        $jsonString = '[{
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
        $collection = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($collection));
        $this->assertEquals(2, count($collection));
        foreach ($collection as $video) {
            $this->assertEquals('Xabbuh\PandaClient\Model\Video', get_class($video));
        }
    }

    /**
     * Test the transformation of an empty JSON encoded collection of videos.
     */
    public function testFromJSONCollectionWithEmptyCollection()
    {
        $jsonString = "[]";
        $collection = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($collection));
        $this->assertEquals(0, count($collection));
    }
}
