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
 * Interface definition for Panda HTTP client implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface HttpClientInterface
{
    /**
     * Sets the cloud id.
     *
     * @param string $cloudId
     */
    public function setCloudId($cloudId);

    /**
     * Returns the cloud id.
     *
     * @return string
     */
    public function getCloudId();

    /**
     * Sets the Account that contains the authorization information.
     *
     * @param Account $account
     */
    public function setAccount(Account $account);

    /**
     * Returns the Account used for generating signatures.
     *
     * @return Account
     */
    public function getAccount();

    /**
     * Helper method to send GET requests to the Panda API.
     *
     * @param string $path   The path to send requests to
     * @param array  $params Url parameters
     *
     * @return string The server response
     */
    public function get($path, array $params = array());

    /**
     * Helper method to send POST requests to the Panda API.
     *
     * @param string $path   The path to send requests to
     * @param array  $params Url parameters
     *
     * @return string The server response
     */
    public function post($path, array $params = array());

    /**
     * Helper method to send PUT requests to the Panda API.
     *
     * @param string $path   The path to send requests to
     * @param  array $params Parameters
     *
     * @return string The server response
     */
    public function put($path, array $params = array());

    /**
     * Helper method to send DELETE requests to the Panda API.
     *
     * @param  string $path   The path to send requests to
     * @param  array  $params Parameters
     *
     * @return string The server response
     */
    public function delete($path, array $params = array());
}
