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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Representation of a Video after it was encoded by applying a Profile.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Encoding implements ModelInterface
{
    private $id;

    private $videoId;

    private $extname;

    private $path;

    private $profileId;

    private $profileName;

    private $duration;

    private $width;

    private $height;

    private $fileSize;

    private $videoBitrate;

    private $audioBitrate;

    private $audioCodec;

    private $videoCodec;

    private $fps;

    private $audioChannels;

    private $audioSampleRate;

    private $status;

    private $mimeType;

    private $encodingProgress;

    private $startedEncodingAt;

    private $encodingTime;

    private $files = array();

    private $errorMessage;

    private $errorClass;

    private $createdAt;

    private $updatedAt;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    public function getExtname()
    {
        return $this->extname;
    }

    public function setExtname($extname)
    {
        $this->extname = $extname;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
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

    public function getDuration() {
        return $this->duration;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getFileSize() {
        return $this->fileSize;
    }

    public function setFileSize($fileSize) {
        $this->fileSize = $fileSize;
    }

    public function getVideoBitrate() {
        return $this->videoBitrate;
    }

    public function setVideoBitrate($videoBitrate) {
        $this->videoBitrate = $videoBitrate;
    }

    public function getAudioBitrate() {
        return $this->audioBitrate;
    }

    public function setAudioBitrate($audioBitrate) {
        $this->audioBitrate = $audioBitrate;
    }

    public function getAudioCodec() {
        return $this->audioCodec;
    }

    public function setAudioCodec($audioCodec) {
        $this->audioCodec = $audioCodec;
    }

    public function getVideoCodec() {
        return $this->videoCodec;
    }

    public function setVideoCodec($videoCodec) {
        $this->videoCodec = $videoCodec;
    }

    public function getFps() {
        return $this->fps;
    }

    public function setFps($fps) {
        $this->fps = $fps;
    }

    public function getAudioChannels() {
        return $this->audioChannels;
    }

    public function setAudioChannels($audioChannels) {
        $this->audioChannels = $audioChannels;
    }

    public function getAudioSampleRate() {
        return $this->audioSampleRate;
    }

    public function setAudioSampleRate($audioSampleRate) {
        $this->audioSampleRate = $audioSampleRate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile($file)
    {
        $this->files->add($file);
    }

    public function removeFile($file)
    {
        $this->files->removeElement($file);
    }

    public function setFiles(array $files)
    {
        $this->files->clear();
        foreach ($files as $file) {
            $this->files->add($file);
        }
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getErrorClass()
    {
        return $this->errorClass;
    }

    public function setErrorClass($errorClass)
    {
        $this->errorClass = $errorClass;
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
