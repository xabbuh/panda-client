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
 * Representation of a Video after it was encoded by applying a Profile.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Encoding extends AbstractMedium
{
    private $videoId;

    private $profileId;

    private $profileName;

    private $encodingProgress;

    private $startedEncodingAt;

    private $encodingTime;

    private $files = array();

    public function getVideoId()
    {
        return $this->videoId;
    }

    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function getProfileName()
    {
        return $this->profileName;
    }

    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;
    }

    public function getEncodingProgress()
    {
        return $this->encodingProgress;
    }

    public function setEncodingProgress($encodingProgress)
    {
        $this->encodingProgress = $encodingProgress;
    }

    public function getStartedEncodingAt()
    {
        return $this->startedEncodingAt;
    }

    public function setStartedEncodingAt($startedEncodingAt)
    {
        $this->startedEncodingAt = $startedEncodingAt;
    }

    public function getEncodingTime()
    {
        return $this->encodingTime;
    }

    public function setEncodingTime($encodingTime)
    {
        $this->encodingTime = $encodingTime;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile($file)
    {
        $this->files[] = $file;
    }

    public function removeFile($file)
    {
        $key = array_search($file, $this->files);

        if (false !== $key) {
            unset($this->files[$key]);
        }
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
    }
}
