<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Util;

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Signer\PandaSigner;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class PandaSignerTest extends TestCase
{
    /**
     * @var PandaSigner
     */
    private $signer;

    protected function setUp()
    {
        $cloudId = '123456789';
        $account = new Account('abcdefgh', 'ijklmnop', 'api.pandastream.com');
        $this->signer = PandaSigner::getInstance($cloudId, $account);
    }

    public function testSignParams()
    {
        $params = array(
            'access_key' => 'abcdefgh',
            'cloud_id' => '123456789',
            'timestamp' => '2011-03-01T15:39:10.260762Z',
        );
        $params = $this->signer->signParams('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $params['signature']
        );
    }

    public function testSignParamsWithoutAccessData()
    {
        $params = array('timestamp' => '2011-03-01T15:39:10.260762Z');
        $params = $this->signer->signParams('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $params['signature']
        );
        $this->assertTrue(isset($params['cloud_id']));
        $this->assertTrue(isset($params['access_key']));
    }

    public function testSignParamsWithoutTimestamp()
    {
        $params = $this->signer->signParams('GET', '/videos.json');

        $this->assertTrue(isset($params['cloud_id']));
        $this->assertTrue(isset($params['access_key']));
        $this->assertTrue(isset($params['timestamp']));
    }

    public function testSignature()
    {
        $params = array(
            'access_key' => 'abcdefgh',
            'cloud_id' => '123456789',
            'timestamp' => '2011-03-01T15:39:10.260762Z',
        );
        $signature = $this->signer->signature('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $signature
        );
    }

    public function testSignatureWithoutAccessData()
    {
        $params = array(
            'timestamp' => '2011-03-01T15:39:10.260762Z',
        );
        $signature = $this->signer->signature('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $signature
        );
    }
}
