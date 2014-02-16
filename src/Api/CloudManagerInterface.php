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
     * @param string         $key   Assign the cloud to this key
     * @param CloudInterface $cloud Cloud to register
     */
    public function registerCloud($key, CloudInterface $cloud);

    /**
     * Tests whether a Cloud is registered.
     *
     * @param string $key The key to check for
     *
     * @return boolean True if a Cloud is registered with the given key,
     *                 false otherwise
     */
    public function hasCloud($key);

    /**
     * Get the cloud for a key.
     *
     * @param string $key The internal key, returns the default Cloud if
     *                    null is given
     *
     * @return CloudInterface The requested cloud
     *
     * @throws \InvalidArgumentException if no cloud for the given key exists
     */
    public function getCloud($key = null);

    /**
     * Changes the key for the default Cloud.
     *
     * @param string $key The new default key
     */
    public function setDefaultCloud($key);

    /**
     * Returns the default Cloud.
     *
     * @return CloudInterface The default Cloud
     */
    public function getDefaultCloud();

    /**
     * Returns all managed clouds.
     *
     * An associative array is returned where the internal keys is associated
     * the configured cloud.
     *
     * @return CloudInterface[]
     */
    public function getClouds();
}
