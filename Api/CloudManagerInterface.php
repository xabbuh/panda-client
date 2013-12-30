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
 * Interface definition for cloud manager implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface CloudManagerInterface
{
    /**
     * Register a cloud on this manager.
     *
     * @param string $key   Assign the cloud to this key
     * @param Cloud  $cloud Cloud to register
     */
    public function registerCloud($key, Cloud $cloud);

    /**
     * Get the cloud for a key.
     *
     * @param string $key The internal key
     *
     * @return Cloud
     *
     * @throws \InvalidArgumentException if no cloud for the given key exists
     */
    public function getCloud($key);

    /**
     * Returns the default cloud.
     *
     * @return Cloud
     */
    public function getDefaultCloud();

    /**
     * Returns all managed clouds.
     *
     * An associative array is returned where the internal keys is associated
     * the configured cloud.
     *
     * @return array
     */
    public function getClouds();
}
