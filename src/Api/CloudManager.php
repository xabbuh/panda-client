<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Api;

/**
 * Manages all configured Panda clouds.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudManager implements CloudManagerInterface
{
    /**
     * Key to which the default Cloud is being mapped
     * @var string
     */
    private $defaultCloudKey;

    /**
     * The Clouds being managed by this CloudManager
     * @var CloudInterface[]
     */
    private $clouds = array();

    /**
     * {@inheritDoc}
     */
    public function registerCloud($key, CloudInterface $cloud)
    {
        $this->clouds[$key] = $cloud;
    }

    /**
     * {@inheritDoc}
     */
    public function hasCloud($key)
    {
        return isset($this->clouds[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function getCloud($key = null)
    {
        if (null === $key) {
            $key = $this->defaultCloudKey;
        }

        if (!$this->hasCloud($key)) {
            throw new \InvalidArgumentException('No cloud for key '.$key.' configured.');
        }

        return $this->clouds[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultCloud($key)
    {
        $this->defaultCloudKey = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultCloud()
    {
        return $this->getCloud($this->defaultCloudKey);
    }

    /**
     * {@inheritDoc}
     */
    public function getClouds()
    {
        return $this->clouds;
    }
}
