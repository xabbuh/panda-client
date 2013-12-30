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
     * @var array
     */
    private $clouds = array();

    /**
     * Constructs the CloudManager.
     *
     * @param string $defaultCloudName Internal key mapped to the default Cloud
     */
    public function __construct($defaultCloudName)
    {
        $this->defaultCloudKey = $defaultCloudName;
    }

    /**
     * {@inheritDoc}
     */
    public function registerCloud($key, Cloud $cloud)
    {
        $this->clouds[$key] = $cloud;
    }

    /**
     * {@inheritDoc}
     */
    public function getCloud($key)
    {
        if (!isset($this->clouds[$key])) {
            throw new \InvalidArgumentException('No cloud for key '.$key.' configured.');
        }

        return $this->clouds[$key];
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
