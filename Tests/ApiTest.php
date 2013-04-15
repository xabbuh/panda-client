<?php

/*
* This file is part of the XabbuhPandaClient package.
*
* (c) Christian Flothmann <christian.flothmann@xabbuh.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Xabbuh\PandaClient\Tests;

use Xabbuh\PandaClient\Api;

/**
 * Tests for the api implementation.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{
    protected $restClient;

    /**
     * The ApiInterface implementation being tested
     * @var \Xabbuh\PandaClient\Api
     */
    protected $api;


    protected function setUp()
    {
        $this->restClient = $this->mockRestClient();
        $this->api = new Api($this->restClient);
    }

    public function testGetRestClient()
    {
        $this->assertEquals($this->restClient, $this->api->getRestClient());
    }

    public function testGetVideos()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with($this->equalTo("/videos.json"))
            ->will($this->returnValue($returnValue));
        $response = $this->api->getVideos();
        $this->assertEquals($returnValue, $response);
    }

    public function testGetVideosForPaginationWithDefaultParameters()
    {
        $returnValue = '{ "videos": [{
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
        "page": 1,
        "per_page": 100,
        "total": 17
        }';
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/videos.json"),
                $this->equalTo(array(
                    "include_root" => true,
                    "page" => 1,
                    "per_page" => 100
                ))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getVideosForPagination();
        $this->assertEquals($returnValue, $response);
    }

    public function testGetVideosForPaginationWithPageParameter()
    {
        $returnValue = '{ "videos": [{
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
        "page": 5,
        "per_page": 100,
        "total": 17
        }';
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/videos.json"),
                $this->equalTo(array(
                    "include_root" => true,
                    "page" => 5,
                    "per_page" => 100
                ))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getVideosForPagination(5);
        $this->assertEquals($returnValue, $response);
    }

    public function testGetVideosForPaginationWithPageAndPerPageParameters()
    {
        $returnValue = '{ "videos": [{
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
        "page": 7,
        "per_page": 25,
        "total": 17
        }';
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/videos.json"),
                $this->equalTo(array(
                    "include_root" => true,
                    "page" => 7,
                    "per_page" => 25
                ))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getVideosForPagination(7, 25);
        $this->assertEquals($returnValue, $response);
    }

    public function testDeleteVideo()
    {
        $id = md5(uniqid());
        $this->restClient->expects($this->once())
            ->method("delete")
            ->with($this->equalTo("/videos/$id.json"))
            ->will($this->returnValue("status: 200"))
        ;
        $this->assertEquals("status: 200", $this->api->deleteVideo($id));
    }

    public function testEncodeVideoByUrl()
    {
        $url = "http://www.example.com/video.mp4";
        $returnValue = '{
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
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
                $this->equalTo("/videos.json"),
                $this->equalTo(array("source_url" => $url))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->encodeVideoByUrl($url);
        $this->assertEquals($returnValue, $response);
    }

    public function testEncodeVideoFile()
    {
        $filename = "video.mp4";
        $returnValue = '{
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
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
            $this->equalTo("/videos.json"),
            $this->equalTo(array("file" => "@$filename"))
        )
            ->will($this->returnValue($returnValue));
        $response = $this->api->encodeVideoFile($filename);
        $this->assertEquals($returnValue, $response);
    }

    public function testRegisterUpload()
    {
        $id = md5(uniqid());
        $location = "http://example.com/$id";
        $filename = "video.mp4";
        $filesize = 114373213;
        $returnValue = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $options = array(
            "file_name" => $filename,
            "file_size" => $filesize,
            "use_all_profiles" => false
        );
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
                $this->equalTo("/videos/upload.json"),
                $this->equalTo($options)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->registerUpload($filename, $filesize);
        $this->assertEquals($returnValue, $response);
    }

    public function testRegisterUploadWithProfilesList()
    {
        $id = md5(uniqid());
        $location = "http://example.com/$id";
        $filename = "video.mpg";
        $filesize = 114373213;
        $returnValue = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $profiles = array("profile1", "profile2");
        $options = array(
            "file_name" => $filename,
            "file_size" => $filesize,
            "profiles" => implode(",", $profiles)
        );
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
            $this->equalTo("/videos/upload.json"),
            $this->equalTo($options)
        )
            ->will($this->returnValue($returnValue));
        $response = $this->api->registerUpload($filename, $filesize, $profiles);
        $this->assertEquals($returnValue, $response);
    }

    public function testRegisterUploadWithAllProfiles()
    {
        $id = md5(uniqid());
        $location = "http://example.com/$id";
        $filename = "video.mpg";
        $filesize = 114373213;
        $returnValue = sprintf(
            '{ "id": "%s", "location": "%s" }',
            $id,
            $location
        );
        $options = array(
            "file_name" => $filename,
            "file_size" => $filesize,
            "use_all_profiles" => true
        );
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
                $this->equalTo("/videos/upload.json"),
                $this->equalTo($options)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->registerUpload($filename, $filesize, null, true);
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodings()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array())
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodings();
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsWithFilter()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array("status" => "success"))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodings(array("status" => "success"));
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsWithStatus()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array("status" => "success"))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsWithStatus("success");
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsWithStatusAndFilter()
    {
        $videoId = md5(uniqid());
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
            $this->equalTo("/encodings.json"),
            $this->equalTo(
                array("status" => "success", "video_id" => $videoId)
            )
        )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsWithStatus(
            "success",
            array("video_id" => $videoId)
        );
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForProfile()
    {
        $profileId = md5(uniqid());
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array("profile_id" => $profileId))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForProfile($profileId);
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForProfileWithFilter()
    {
        $profileId = md5(uniqid());
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
            $this->equalTo("/encodings.json"),
            $this->equalTo(
                array("profile_id" => $profileId, "status" => "success")
            )
        )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForProfile(
            $profileId,
            array("status" => "success")
        );
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForProfileByName()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array("profile_name" => "h264"))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForProfileByName("h264");
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForProfileByNameWithFilter()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
            $this->equalTo("/encodings.json"),
            $this->equalTo(
                array("profile_name" => "h264", "status" => "success")
            )
        )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForProfileByName(
            "h264",
            array("status" => "success")
        );
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForVideo()
    {
        $id = md5(uniqid());
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
                $this->equalTo("/encodings.json"),
                $this->equalTo(array("video_id" => $id))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForVideo($id);
        $this->assertEquals($returnValue, $response);
    }

    public function testGetEncodingsForVideoWithFilter()
    {
        $id = md5(uniqid());
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with(
            $this->equalTo("/encodings.json"),
            $this->equalTo(
                    array("video_id" => $id, "status" => "success")
                )
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->getEncodingsForVideo(
            $id,
            array("status" => "success")
        );
        $this->assertEquals($returnValue, $response);
    }

    public function testGetProfiles()
    {
        $returnValue = '[{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with($this->equalTo("/profiles.json"))
            ->will($this->returnValue($returnValue));
        $response = $this->api->getProfiles();
        $this->assertEquals($returnValue, $response);
    }

    public function testGetProfile()
    {
        $id = md5(uniqid());
        $returnValue = '{
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
        $this->restClient->expects($this->once())
            ->method("get")
            ->with($this->equalTo("/profiles/$id.json"))
            ->will($this->returnValue($returnValue));
        $response = $this->api->getProfile($id);
        $this->assertEquals($returnValue, $response);
    }

    public function testAddProfile()
    {
        $returnValue = '{
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
        $data = array();
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
                "/profiles.json",
                $this->equalTo($data)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->addProfile($data);
        $this->assertEquals($returnValue, $response);
    }

    public function testAddProfileFromPreset()
    {
        $returnValue = '{
          "id":"40d9f8711d64aaa74f88462e9274f39a",
          "title": "H264 (MP4)",
          "name": "h264",
          "extname":".mp4",
          "width":480,
          "height":320,
          "audio_bitrate": 128,
          "video_bitrate": 500,
          "preset_name": "h264",
          "created_at":"2009/10/14 18:36:30 +0000",
          "updated_at":"2009/10/14 19:38:42 +0000"
        }';
        $this->restClient->expects($this->once())
            ->method("post")
            ->with(
                "/profiles.json",
                $this->equalTo(array("preset_name" => "h264"))
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->addProfileFromPreset("h264");
        $this->assertEquals($returnValue, $response);
    }

    public function testSetProfile()
    {
        $id = md5(uniqid());
        $returnValue = "{
              'id':'40d9f8711d64aaa74f88462e9274f39a',
              'title':'The best custom profile',
              'extname':'.mp4',
              'width':320,
              'height':240,
              'audio_bitrate':128,
              'video_bitrate':500,
              'command':'ffmpeg -i \$input_file\$ -c:a libfaac \$audio_bitrate\$ -c:v libx264 \$video_bitrate\$ -preset medium \$filters\$ -y \$output_file\$',
              'created_at':'2009/10/14 18:36:30 +0000',
              'updated_at':'2009/10/14 19:38:42 +0000'
            }
        ";
        $data = array("title" => "The best custom profile");
        $this->restClient->expects($this->once())
            ->method("put")
            ->with(
                $this->equalTo("/profiles/$id.json"),
                $this->equalTo($data)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->setProfile($id, $data);
        $this->assertEquals($returnValue, $response);
    }

    public function testDeleteProfile()
    {
        $id = md5(uniqid());
        $this->restClient->expects($this->once())
            ->method("delete")
            ->with($this->equalTo("/profiles/$id.json"))
            ->will($this->returnValue("status: 200"));
        $this->assertEquals("status: 200", $this->api->deleteProfile($id));
    }

    public function testGetCloud()
    {
        $id = md5(uniqid());
        $returnValue = '{
          "id": "e122090f4e506ae9ee266c3eb78a8b67",
          "name": "my_first_cloud",
          "s3_videos_bucket": "my-example-bucket",
          "s3_private_access":false,
          "url": "http://my-example-bucket.s3.amazonaws.com/",
          "created_at": "2010/03/18 12:56:04 +0000",
          "updated_at": "2010/03/18 12:59:06 +0000"
        }';
        $this->restClient->expects($this->once())
            ->method("get")
            ->with($this->equalTo("/clouds/$id.json"))
            ->will($this->returnValue($returnValue));
        $response = $this->api->getCloud($id);
        $this->assertEquals($returnValue, $response);
    }

    public function testSetCloud()
    {
        $id = md5(uniqid());
        $data = array(
            "name" => "my_first_cloud",
            "s3_videos_bucket" => "my_own_bucket",
            "aws_access_key" => "XQwEwFR",
            "aws_secret_key" => "XoSV2f"
        );
        $returnValue = '{
          "id": "e122090f4e506ae9ee266c3eb78a8b67",
          "name": "my_first_cloud",
          "s3_videos_bucket": "my-example-bucket",
          "s3_private_access":false,
          "url": "http://my-example-bucket.s3.amazonaws.com/",
          "created_at": "2010/03/18 12:56:04 +0000",
          "updated_at": "2010/03/18 12:59:06 +0000"
        }';
        $this->restClient->expects($this->once())
            ->method("put")
            ->with(
                $this->equalTo("/clouds/$id.json"),
                $this->equalTo($data)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->setCloud($id, $data);
        $this->assertEquals($returnValue, $response);
    }

    public function testGetNotifications()
    {
        $returnValue = '{
          "url": "null",
          "events": {
            "video_created": false,
            "video_encoded": false,
            "encoding_progress": false,
            "encoding_completed": false
          }
        }';
        $this->restClient->expects($this->once())
            ->method("get")
            ->with($this->equalTo("/notifications.json"))
            ->will($this->returnValue($returnValue));
        $response = $this->api->getNotifications();
        $this->assertEquals($returnValue, $response);
    }

    public function testSetNotifications()
    {
        $data = array(
            "url" => "http://example.com/panda_notification",
            "events['video_encoded']" => true
        );
        $returnValue = '{
          "url": "null",
          "events": {
            "video_created": false,
            "video_encoded": false,
            "encoding_progress": false,
            "encoding_completed": false
          }
        }';
        $this->restClient->expects($this->once())
            ->method("put")
            ->with(
                $this->equalTo("/notifications.json"),
                $this->equalTo($data)
            )
            ->will($this->returnValue($returnValue));
        $response = $this->api->setNotifications($data);
        $this->assertEquals($returnValue, $response);
    }

    private function mockRestClient()
    {
        return $this->getMock("Xabbuh\\PandaClient\\RestClientInterface");
    }
}
