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
 * Representation of a cloud's data.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Cloud implements ModelInterface
{
    private $id;

    private $name;

    private $s3VideosBucket;

    private $s3PrivateAccess;

    private $url;

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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getS3VideosBucket()
    {
        return $this->s3VideosBucket;
    }

    public function setS3VideosBucket($s3VideosBucket)
    {
        $this->s3VideosBucket = $s3VideosBucket;
    }

    public function isS3AccessPrivate()
    {
        return $this->s3PrivateAccess;
    }

    public function setS3PrivateAccess($s3PrivateAccess)
    {
        $this->s3PrivateAccess = $s3PrivateAccess;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
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
