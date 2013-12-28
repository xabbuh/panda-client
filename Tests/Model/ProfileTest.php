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

use Xabbuh\PandaClient\Model\Profile;

/**
 * Test the Profile model class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ProfileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getter and setter methods.
     */
    public function testGetterSetter()
    {
        $profile = new Profile();
        $profile->setId("40d9f8711d64aaa74f88462e9274f39a");
        $profile->setTitle("MP4 (H.264)");
        $profile->setName("h264");
        $profile->setPresetName("My preset");
        $profile->setExtname(".mp4");
        $profile->setWidth(320);
        $profile->setHeight(240);
        $profile->setUpscale(true);
        $profile->setAspectMode("letterbox");
        $profile->setTwoPass(false);
        $profile->setVideoBitrate(500);
        $profile->setFps(29.97);
        $profile->setKeyframeInterval(250);
        $profile->setKeyframeRate(0.5);
        $profile->setAudioBitrate(128);
        $profile->setAudioSampleRate(44100);
        $profile->setAudioChannels("");
        $profile->setClipLength("00:20:00");
        $profile->setClipOffset("00:00:10");
        $profile->setFrameCount(5);
        $profile->setCommand("ffmpeg -i \$input_file\$ -c:a libfaac \$audio_bitrate\$ -c:v libx264 \$video_bitrate\$ -preset medium \$filters\$ -y \$output_file\$");
        $profile->setCreatedAt("2009/10/14 18:36:30 +0000");
        $profile->setUpdatedAt("2009/10/14 19:38:42 +0000");

        $this->assertEquals("40d9f8711d64aaa74f88462e9274f39a", $profile->getId());
        $this->assertEquals("MP4 (H.264)", $profile->getTitle());
        $this->assertEquals("h264", $profile->getName());
        $this->assertEquals("My preset", $profile->getPresetName());
        $this->assertEquals(".mp4", $profile->getExtname());
        $this->assertEquals(320, $profile->getWidth());
        $this->assertEquals(240, $profile->getHeight());
        $this->assertTrue($profile->getUpscale());
        $this->assertEquals("letterbox", $profile->getAspectMode());
        $this->assertFalse($profile->getTwoPass());
        $this->assertEquals(500, $profile->getVideoBitrate());
        $this->assertEquals(29.97, $profile->getFps());
        $this->assertEquals(250, $profile->getKeyframeInterval());
        $this->assertEquals(0.5, $profile->getKeyframeRate());
        $this->assertEquals(128, $profile->getAudioBitrate());
        $this->assertEquals(44100, $profile->getAudioSampleRate());
        $this->assertEquals("", $profile->getAudioChannels());
        $this->assertEquals("00:20:00", $profile->getClipLength());
        $this->assertEquals("00:00:10", $profile->getClipOffset());
        $this->assertEquals(5, $profile->getFrameCount());
        $this->assertEquals("ffmpeg -i \$input_file\$ -c:a libfaac \$audio_bitrate\$ -c:v libx264 \$video_bitrate\$ -preset medium \$filters\$ -y \$output_file\$", $profile->getCommand());
        $this->assertEquals("2009/10/14 18:36:30 +0000", $profile->getCreatedAt());
        $this->assertEquals("2009/10/14 19:38:42 +0000", $profile->getUpdatedAt());
    }
}
