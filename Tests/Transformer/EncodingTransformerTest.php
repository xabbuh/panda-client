<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Transformers;

use Xabbuh\PandaClient\Transformer\EncodingTransformer;

/**
 * Tests for the EncodingTransformer class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class EncodingTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EncodingTransformer
     */
    private static $transformer;


    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        self::$transformer = new EncodingTransformer();
    }

    /**
     * Test the transformation of a single encoding
     */
    public function testFromJSON()
    {
        $jsonString = '{
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
        }';
        $encoding = self::$transformer->fromJSON($jsonString);
        $this->assertEquals('2f8760b7e0d4c7dbe609b5872be9bc3b', $encoding->getId());
        $this->assertEquals('d891d9a45c698d587831466f236c6c6c', $encoding->getVideoId());
        $this->assertEquals('.mp4', $encoding->getExtname());
        $this->assertEquals('2f8760b7e0d4c7dbe609b5872be9bc3b', $encoding->getPath());
        $this->assertEquals('40d9f8711d64aaa74f88462e9274f39a', $encoding->getProfileId());
        $this->assertEquals('h264', $encoding->getProfileName());
        $this->assertEquals('success', $encoding->getStatus());
        $this->assertEquals(99, $encoding->getEncodingProgress());
        $this->assertEquals(300, $encoding->getWidth());
        $this->assertEquals(240, $encoding->getHeight());
        $this->assertEquals('2009/10/13 21:28:45 +0000', $encoding->getStartedEncodingAt());
        $this->assertEquals(9000, $encoding->getEncodingTime());
        $this->assertEquals(
            array('2f8760b7e0d4c7dbe609b5872be9bc3b.mp4'),
            $encoding->getFiles()->getValues()
        );
        $this->assertEquals('2009/10/13 20:58:29 +0000', $encoding->getCreatedAt());
        $this->assertEquals('2009/10/13 21:30:34 +0000', $encoding->getUpdatedAt());
    }

    /**
     * Test that a non-existing property throws an exception.
     */
    public function testFromJSONWithInvalidEncoding()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Transformation error: Unknown property videosid in model'
        );
        $jsonString = '{
          "id":"d891d9a45c698d587831466f236c6c6c",
          "videosid":"d891d9a45c698d587831466f236c6c6c"
        }';
        self::$transformer->fromJson($jsonString);
    }

    /**
     * Test the transformation of a collection of encodings.
     */
    public function testFromJSONCollection()
    {
        $jsonString = '[{
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
        $encodings = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($encodings));
        $this->assertEquals(2, count($encodings));
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Encoding',
            get_class($encodings[0])
        );
        $this->assertEquals(
            '2f8760b7e0d4c7dbe609b5872be9bc3b',
            $encodings[0]->getId()
        );
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Encoding',
            get_class($encodings[1])
        );
        $this->assertEquals(
            'ab658d9599ca70966cfd0f53c186712b',
            $encodings[1]->getId()
        );
    }

    /**
     * Test the transformation of an empty JSON encoded collection of encodings.
     */
    public function testFromJSONCollectionWithEmptyCollection()
    {
        $jsonString = '[]';
        $collection = self::$transformer->fromJSONCollection($jsonString);
        $this->assertTrue(is_array($collection));
        $this->assertEquals(0, count($collection));
    }
}
