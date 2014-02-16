<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Util;

use Xabbuh\PandaClient\Api\Account;

/**
 * Sign Panda HTTP requests.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Signing
{
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

    private function __construct()
    {
    }

    /**
     * Sets the cloud id.
     *
     * @param string $cloudId
     */
    public function setCloudId($cloudId)
    {
        $this->cloudId = $cloudId;
    }

    /**
     * Returns the cloud id.
     *
     * @return string
     */
    public function getCloudId()
    {
        return $this->cloudId;
    }

    /**
     * Sets the Account that contains the authorization information.
     *
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Returns the Account used for generating signatures.
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Generates the signature for a given set of request parameters and add
     * this signature to the set of parameters.
     *
     * @param string   $method The HTTP method
     * @param string   $path   The request path
     * @param string[] $params Request parameters
     *
     * @return string[] The signed parameters
     */
    public function signParams($method, $path, array $params = array())
    {
        if (!isset($params['cloud_id'])) {
            $params['cloud_id'] = $this->cloudId;
        }

        if (!isset($params['access_key'])) {
            $params['access_key'] = $this->account->getAccessKey();
        }

        if (!isset($params['timestamp'])) {
            $oldTz = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $params['timestamp'] = date('c');
            date_default_timezone_set($oldTz);
        }

        // generate the signature
        $params['signature'] = $this->signature($method, $path, $params);

        return $params;
    }

    /**
     * Generates the signature for an API requests based on its parameters.
     *
     * @param string   $method The HTTP method
     * @param string   $path   The request path
     * @param string[] $params Request parameters
     *
     * @return string The generated signature
     */
    public function signature($method, $path, array $params = array())
    {
        if (!isset($params['cloud_id'])) {
            $params['cloud_id'] = $this->cloudId;
        }

        if (!isset($params['access_key'])) {
            $params['access_key'] = $this->account->getAccessKey();
        }

        if (!isset($params['timestamp'])) {
            $oldTz = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $params['timestamp'] = date('c');
            date_default_timezone_set($oldTz);
        }

        ksort($params);

        if (isset($params['file'])) {
            unset($params['file']);
        }

        $canonicalQueryString = str_replace(
            array('+', '%5B', '%5D'),
            array('%20', '[', ']'),
            http_build_query($params)
        );
        $stringToSign = sprintf(
            "%s\n%s\n%s\n%s",
            strtoupper($method),
            $this->account->getApiHost(),
            $path,
            $canonicalQueryString
        );
        $hmac = hash_hmac('sha256', $stringToSign, $this->account->getSecretKey(), true);

        return base64_encode($hmac);
    }

    /**
     * Returns a Signing instance for a Cloud.
     *
     * @param string  $cloudId The cloud id
     * @param Account $account The authorization details
     *
     * @return Signing The generated Signing instance
     */
    public static function getInstance($cloudId, Account $account)
    {
        $signing = new Signing();
        $signing->setCloudId($cloudId);
        $signing->setAccount($account);

        return $signing;
    }
}
