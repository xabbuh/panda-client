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

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Xabbuh\PandaClient\Exception\ApiException;
use Xabbuh\PandaClient\Exception\HttpException;
use Xabbuh\PandaClient\Signer\PandaSigner;

/**
 * Panda REST client implementation using the Httplug library.
 *
 * Sends signed requests (GET, POST, PUT or DELETE) to the Panda encoding
 * webservice.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 * @author Christophe Coevoet <stof@notk.org>
 */
class HttplugClient implements HttpClientInterface
{
    /**
     * @var \Http\Client\HttpClient
     */
    private $httpClient;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

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
     * HttplugClient constructor.
     *
     * @param \Http\Client\HttpClient|null $httpClient
     * @param RequestFactory|null          $requestFactory
     * @param StreamFactory|null           $streamFactory
     */
    public function __construct(\Http\Client\HttpClient $httpClient = null, RequestFactory $requestFactory = null, StreamFactory $streamFactory = null)
    {
        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->streamFactory = $streamFactory ?: StreamFactoryDiscovery::find();
    }

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
        $signer = PandaSigner::getInstance($this->cloudId, $this->account);
        $params = $signer->signParams($method, $path, $params);

        // ensure to use relative paths
        if (0 === strpos($path, '/')) {
            $path = substr($path, 1);
        }

        // append request parameters to the URL
        if ('GET' === $method || 'DELETE' === $method) {
            $path .= '?'.http_build_query($params, '', '&');
        }

        $body = null;
        $headers = array();

        if ('PUT' === $method || 'POST' === $method) {
            $body = $this->streamFactory->createStream(http_build_query($params, '', '&'));
            $headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
        }

        // prepare the request
        $uri = 'https://'.$this->account->getApiHost().'/v2/'.$path;
        $request = $this->requestFactory->createRequest($method, $uri, $headers, $body);

        // and execute it
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Exception $e) {
            // throw an exception if the http request failed
            throw new HttpException($e->getMessage(), $e->getCode());
        }

        $responseBody = (string) $response->getBody();

        // throw an API exception if the API response is not valid
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 207) {
            $decodedResponse = json_decode($responseBody);
            $message = $decodedResponse->error.': '.$decodedResponse->message;

            throw new ApiException($message, $response->getStatusCode());
        }

        return $responseBody;
    }
}
