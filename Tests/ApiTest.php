<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests;

use Xabbuh\PandaClient\Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultApi()
    {
        $accessKey = md5(uniqid());
        $secretKey = md5(uniqid());
        $apiHost = 'api-eu.pandastream.com';
        $config = array(
            'accounts' => array(
                'default' => array(
                    'access_key' => $accessKey,
                    'secret_key' => $secretKey,
                    'api_host'   => $apiHost,
                ),
            ),
            'clouds' => array(
                'default' => array(
                    'id'      => md5(uniqid()),
                    'account' => 'default',
                ),
            ),
        );
        $api = Api::getInstance($config);

        $accountManager = $api->getAccountManager();
        $account = $accountManager->getAccount('default');
        $this->assertEquals($accessKey, $account->getAccessKey());
        $this->assertEquals($secretKey, $account->getSecretKey());
        $this->assertEquals($apiHost, $account->getApiHost());

        $cloudManager = $api->getCloudManager();
        $this->assertEquals(1, count($cloudManager->getClouds()));
    }

    public function testWithoutAccount()
    {
        $config = array(
            'clouds' => array(
                'default' => array(
                    'id' => md5(uniqid()),
                    'account' => 'default',
                ),
            ),
        );

        $this->setExpectedException('\InvalidArgumentException');
        Api::getInstance($config);
    }

    public function testWithoutCloud()
    {
        $accessKey = md5(uniqid());
        $secretKey = md5(uniqid());
        $apiHost = 'api-eu.pandastream.com';
        $config = array(
            'accounts' => array(
                'default' => array(
                    'access_key' => $accessKey,
                    'secret_key' => $secretKey,
                    'api_host' => $apiHost,
                ),
            ),
        );

        $this->setExpectedException('\InvalidArgumentException');
        Api::getInstance($config);
    }

    public function testCloudWithoutAccount()
    {
        $accessKey = md5(uniqid());
        $secretKey = md5(uniqid());
        $apiHost = 'api-eu.pandastream.com';
        $config = array(
            'accounts' => array(
                'default' => array(
                    'access_key' => $accessKey,
                    'secret_key' => $secretKey,
                    'api_host' => $apiHost,
                ),
            ),
            'clouds' => array(
                'default' => array(
                    'id' => md5(uniqid()),
                    'account' => 'default2',
                ),
            ),
        );

        $this->setExpectedException('\InvalidArgumentException');
        Api::getInstance($config);
    }
}
 