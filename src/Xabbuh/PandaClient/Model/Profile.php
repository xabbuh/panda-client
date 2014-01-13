<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Model;

/**
 * Model representing a profile configuration.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Profile implements ModelInterface
{
    private $id;

    private $title;

    private $name;

    private $presetName;

    private $extname;

    private $width;

    private $height;

    private $upscale;

    private $aspectMode;

    private $twoPass;

    private $videoBitrate;

    private $fps;

    private $keyframeInterval;

    private $keyframeRate;

    private $audioBitrate;

    private $audioSampleRate;

    private $audioChannels;

    private $clipLength;

    private $clipOffset;

    private $frameCount;

    private $command;

    private $createdAt;

    private $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPresetName()
    {
        return $this->presetName;
    }

    public function setPresetName($presetName)
    {
        $this->presetName = $presetName;
    }

    public function getExtname()
    {
        return $this->extname;
    }

    public function setExtname($extname)
    {
        $this->extname = $extname;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getUpscale()
    {
        return $this->upscale;
    }

    public function setUpscale($upscale)
    {
        $this->upscale = $upscale;
    }

    public function getAspectMode()
    {
        return $this->aspectMode;
    }

    public function setAspectMode($aspectMode)
    {
        $this->aspectMode = $aspectMode;
    }

    public function getTwoPass()
    {
        return $this->twoPass;
    }

    public function setTwoPass($twoPass)
    {
        $this->twoPass = $twoPass;
    }

    public function getVideoBitrate()
    {
        return $this->videoBitrate;
    }

    public function setVideoBitrate($videoBitrate)
    {
        $this->videoBitrate = $videoBitrate;
    }

    public function getFps()
    {
        return $this->fps;
    }

    public function setFps($fps)
    {
        $this->fps = $fps;
    }

    public function getKeyframeInterval()
    {
        return $this->keyframeInterval;
    }

    public function setKeyframeInterval($keyframeInterval)
    {
        $this->keyframeInterval = $keyframeInterval;
    }

    public function getKeyframeRate()
    {
        return $this->keyframeRate;
    }

    public function setKeyframeRate($keyframeRate)
    {
        $this->keyframeRate = $keyframeRate;
    }

    public function getAudioBitrate()
    {
        return $this->audioBitrate;
    }

    public function setAudioBitrate($audioBitrate)
    {
        $this->audioBitrate = $audioBitrate;
    }

    public function getAudioSampleRate()
    {
        return $this->audioSampleRate;
    }

    public function setAudioSampleRate($audioSampleRate)
    {
        $this->audioSampleRate = $audioSampleRate;
    }

    public function getAudioChannels()
    {
        return $this->audioChannels;
    }

    public function setAudioChannels($audioChannels)
    {
        $this->audioChannels = $audioChannels;
    }

    public function getClipLength()
    {
        return $this->clipLength;
    }

    public function setClipLength($clipLength)
    {
        $this->clipLength = $clipLength;
    }

    public function getClipOffset()
    {
        return $this->clipOffset;
    }

    public function setClipOffset($clipOffset)
    {
        $this->clipOffset = $clipOffset;
    }

    public function getFrameCount()
    {
        return $this->frameCount;
    }

    public function setFrameCount($frameCount)
    {
        $this->frameCount = $frameCount;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setCommand($command)
    {
        $this->command = $command;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
