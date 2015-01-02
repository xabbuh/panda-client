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
class Video extends AbstractMedium
{
    private $originalFilename;

    private $sourceUrl;

    private $payload;

    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;
    }

    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }
}
