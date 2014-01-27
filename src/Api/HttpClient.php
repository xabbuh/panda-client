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

use Guzzle\Http\Client;
use Xabbuh\PandaClient\Exception\ApiException;
use Xabbuh\PandaClient\Exception\HttpException;
use Xabbuh\PandaClient\Util\Signing;

/**
 * Panda REST client implementation using the PHP cURL extension.
 *
 * Send signed requests (GET, POST, PUT or DELETE) to the Panda encoding
 * webservice.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var Client
     */
    private $guzzleClient;

    /**
     * Panda cloud id
     *
     * @var string
     */
    private $cloudId;

    /**
     * Panda Account
     *
     * @var Account
     */
    private $account;

    /**
     * {@inheritDoc}
     */
    public function setCloudId($cloudId)
    {
        $this->cloudId = $cloudId;
    }

    /**
     * {@inheritDoc}
     */
    public function getCloudId()
    {
        return $this->cloudId;
    }

    /**
     * {@inheritDoc}
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * {@inheritDoc}
     */
    public function getAccount()
    {
        return $this->account;
    }

    public function setGuzzleClient(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $params = array())
    {
        return $this->request('GET', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, array $params = array())
    {
        return $this->request('POST', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, array $params = array())
    {
        return $this->request('PUT', $path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, array $params = array())
    {
        return $this->request('DELETE', $path, $params);
    }

    /**
     * Send signed HTTP requests to the API server.
     *
     * @param string $method HTTP method (GET, POST, PUT or DELETE)
     * @param string $path   Request path
     * @param array  $params Additional request parameters
     *
     * @return string The API server's response
     *
     * @throws ApiException  if an API error occurs
     * @throws HttpException if the request fails
     */
    private function request($method, $path, array $params)
    {
        // sign the request parameters
        $signing = Signing::getInstance($this->cloudId, $this->account);
        $params = $signing->signParams($method, $path, $params);

        // ensure to use relative paths
        if (0 === strpos($path, '/')) {
            $path = substr($path, 1);
        }

        // append request parameters to the URL
        if ('GET' === $method || 'DELETE' === $method) {
            $path .= '?'.http_build_query($params);
        }

        // prepare the request
        switch ($method) {
            case 'GET':
                $request = $this->guzzleClient->get($path);
                break;
            case 'DELETE':
                $request = $this->guzzleClient->delete($path);
                break;
            case 'PUT':
                $request = $this->guzzleClient->put($path, null, $params);
                break;
            case 'POST':
                $request = $this->guzzleClient->post($path, null, $params);
                break;
        }

        // and execute it
        try {
            $response = $request->send();
        } catch (\Exception $e) {
            // throw an exception if the http request failed
            throw new HttpException($e->getMessage(), $e->getCode());
        }

        // throw an API exception if the API response is not valid
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 207) {
            $decodedResponse = json_decode($response->getBody(true));
            $message = $decodedResponse->error.': '.$decodedResponse->message;

            throw new ApiException($message, $response->getStatusCode());
        }

        return $response->getBody(true);
    }
}
