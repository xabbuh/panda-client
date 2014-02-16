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
 * Representation of a panda account.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Account
{
    /**
     * The access key
     * @var string
     */
    private $accessKey;

    /**
     * The secret key
     * @var string
     */
    private $secretKey;

    /**
     * The api host
     * @var string
     */
    private $apiHost;


    /**
     * Constructs an Account with the given authorisation data.
     *
     * @param string $accessKey The access key
     * @param string $secretKey The secret key
     * @param string $apiHost The api host
     */
    public function __construct($accessKey, $secretKey, $apiHost)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->apiHost = $apiHost;
    }

    /**
     * Returns the access key.
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Returns the secret key.
     *
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Returns the api host.
     *
     * @return string
     */
    public function getApiHost()
    {
        return $this->apiHost;
    }
}
