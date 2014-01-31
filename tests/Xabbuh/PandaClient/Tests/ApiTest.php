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
        $api = new Api($this->createDefaultConfig($accessKey, $secretKey));

        $accountManager = $api->getAccountManager();
        $account = $accountManager->getAccount('default');

        $this->assertEquals($accessKey, $account->getAccessKey());
        $this->assertEquals($secretKey, $account->getSecretKey());
        $this->assertEquals('api-eu.pandastream.com', $account->getApiHost());

        $cloudManager = $api->getCloudManager();

        $this->assertEquals(1, count($cloudManager->getClouds()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithoutAccount()
    {
        $config = $this->createDefaultConfig();
        unset($config['accounts']);
        new Api($config);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithoutCloud()
    {
        $config = $this->createDefaultConfig();
        unset($config['clouds']);
        new Api($config);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCloudWithoutAccount()
    {
        $config = $this->createDefaultConfig();
        unset($config['clouds']['default']['account']);
        new Api($config);
    }

    public function testCloud()
    {
        $api = new Api($this->createDefaultConfig());
        $cloud = $api->getCloud('default');

        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Api\HttpClientInterface',
            $cloud->getHttpClient()
        );
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\TransformerRegistryInterface',
            $cloud->getTransformers()
        );
    }

    public function testHttpClient()
    {
        $api = new Api($this->createDefaultConfig(null, null, 'foo'));
        $httpClient = $api->getCloud('default')->getHttpClient();

        $this->assertEquals('foo', $httpClient->getCloudId());
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Api\Account',
            $httpClient->getAccount()
        );
    }

    public function testCloudManager()
    {
        $api = new Api($this->createDefaultConfig(null, null, 'foo'));
        $cloudManager = $api->getCloudManager();

        $this->assertEquals(
            $cloudManager->getCloud('default'),
            $cloudManager->getDefaultCloud()
        );
    }

    public function testAccountManager()
    {
        $api = new Api($this->createDefaultConfig(null, null, 'foo'));
        $accountManager = $api->getAccountManager();

        $this->assertEquals(
            $accountManager->getAccount('default'),
            $accountManager->getDefaultAccount()
        );
    }

    private function createDefaultConfig($accessKey = null, $secretKey = null, $cloudId = null)
    {
        if (null === $accessKey) {
            $accessKey = md5(uniqid());
        }

        if (null === $secretKey) {
            $secretKey = md5(uniqid());
        }

        if (null === $cloudId) {
            $cloudId = md5(uniqid());
        }

        return array(
            'accounts' => array(
                'default' => array(
                    'access_key' => $accessKey,
                    'secret_key' => $secretKey,
                    'api_host'   => 'api-eu.pandastream.com',
                ),
            ),
            'clouds' => array(
                'default' => array(
                    'id'      => $cloudId,
                    'account' => 'default',
                ),
            ),
        );
    }
}
