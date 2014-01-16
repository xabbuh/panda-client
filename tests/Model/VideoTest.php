<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Model;

use Xabbuh\PandaClient\Model\Video;

/**
 * Test the Video model class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class VideoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getter and setter methods.
     */
    public function testGetterSetter()
    {
        $video = new Video();
        $video->setId('2f8760b7e0d4c7dbe609b5872be9bc3b');
        $video->setOriginalFilename('test.mp4');
        $video->setExtname('.mp4');
        $video->setPath('2f8760b7e0d4c7dbe609b5872be9bc3b');
        $video->setVideoCodec('h264');
        $video->setAudioCodec('aac');
        $video->setStatus('success');
        $video->setWidth(300);
        $video->setHeight(240);
        $video->setVideoBitrate(500);
        $video->setAudioBitrate(128);
        $video->setFps(29);
        $video->setAudioChannels(2);
        $video->setAudioSampleRate(48000);
        $video->setMimeType('video/mp4');
        $video->setDuration(14000);
        $video->setFileSize(39458349);
        $video->setErrorClass('Aborted');
        $video->setErrorMessage('User aborted transcoding');
        $video->setCreatedAt('2009/10/13 20:58:29 +0000');
        $video->setUpdatedAt('2009/10/13 21:30:34 +0000');

        $this->assertEquals('2f8760b7e0d4c7dbe609b5872be9bc3b', $video->getId());
        $this->assertEquals('test.mp4', $video->getOriginalFilename());
        $this->assertEquals('.mp4', $video->getExtname());
        $this->assertEquals('2f8760b7e0d4c7dbe609b5872be9bc3b', $video->getPath());
        $this->assertEquals('h264', $video->getVideoCodec());
        $this->assertEquals('aac', $video->getAudioCodec());
        $this->assertEquals('success', $video->getStatus());
        $this->assertEquals(300, $video->getWidth());
        $this->assertEquals(240, $video->getHeight());
        $this->assertEquals(500, $video->getVideoBitrate());
        $this->assertEquals(128, $video->getAudioBitrate());
        $this->assertEquals(29, $video->getFps());
        $this->assertEquals(2, $video->getAudioChannels());
        $this->assertEquals(48000, $video->getAudioSampleRate());
        $this->assertEquals('video/mp4', $video->getMimeType());
        $this->assertEquals(14000, $video->getDuration());
        $this->assertEquals(39458349, $video->getFileSize());
        $this->assertEquals('Aborted', $video->getErrorClass());
        $this->assertEquals('User aborted transcoding', $video->getErrorMessage());
        $this->assertEquals('2009/10/13 20:58:29 +0000', $video->getCreatedAt());
        $this->assertEquals('2009/10/13 21:30:34 +0000', $video->getUpdatedAt());
    }
}
