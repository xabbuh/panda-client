<?php

/*
* This file is part of the XabbuhPandaClient package.
*
* (c) Christian Flothmann <christian.flothmann@xabbuh.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Xabbuh\PandaClient;
/**
 * Interface definition for Panda REST client implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface RestClientInterface
{
    /**
     * Get the cloud id.
     *
     * @return string
     */
    public function getCloudId();

    /**
     * Get the cloud's access key.
     *
     * @return string
     */
    public function getAccessKey();

    /**
     * Get the cloud's secret key.
     *
     * @return string
     */
    public function getSecretKey();

    /**
     * Get the api host.
     *
     * @return string
     */
    public function getApiHost();

    /**
     * Helper method to send GET requests to the Panda API.
     *
     * @param string $path The path to send requests to
     * @param array $params Url parameters
     * @return string The server response
     */
    public function get($path, array $params = array());

    /**
     * Helper method to send POST requests to the Panda API.
     *
     * @param string $path The path to send requests to
     * @param array $params Url parameters
     * @return string The server response
     */
    public function post($path, array $params = array());

    /**
     * Helper method to send PUT requests to the Panda API.
     *
     * @param string $path The path to send requests to
     * @param array $params Parameters
     * @return string The server response
     */
    public function put($path, array $params = array());

    /**
     * Helper method to send DELETE requests to the Panda API.
     *
     * @param string $path The path to send requests to
     * @param array $params Parameters
     * @return string The server response
     */
    public function delete($path, array $params = array());

    /**
     * Generate signature for a given set of request parameters and add the
     * signature to the parameters.
     *
     * @param string $method The HTTP method
     * @param string $path The request path
     * @param array $params Request parameters
     * @return array The signed parameters
     */
    public function signParams($method, $path, array $params);

    /**
     * Generates the signature for an API requests based on its parameters.
     *
     * @param string $method The HTTP method
     * @param string $path The request path
     * @param array $params Request parameters
     * @return string The generated signature
     */
    public function signature($method, $path, array $params);
}
