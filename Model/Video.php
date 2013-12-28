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
 * Representation of a video as it is returned by the Panda encoding service.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Video implements ModelInterface
{
    private $id;
    
    private $createdAt;
    
    private $updatedAt;
    
    private $originalFilename;
    
    private $extname;
    
    private $sourceUrl;
    
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
    
    private $path;

    private $errorMessage;

    private $errorClass;
    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getOriginalFilename() {
        return $this->originalFilename;
    }

    public function setOriginalFilename($originalFilename) {
        $this->originalFilename = $originalFilename;
    }

    public function getExtname() {
        return $this->extname;
    }

    public function setExtname($extname) {
        $this->extname = $extname;
    }

    public function getSourceUrl() {
        return $this->sourceUrl;
    }

    public function setSourceUrl($sourceUrl) {
        $this->sourceUrl = $sourceUrl;
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

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
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
}
