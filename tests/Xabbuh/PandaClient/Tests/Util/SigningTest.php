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

use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Util\Signing;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class SigningTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Signing
     */
    private $signing;

    protected function setUp()
    {
        $cloudId = '123456789';
        $account = new Account('abcdefgh', 'ijklmnop', 'api.pandastream.com');
        $this->signing = Signing::getInstance($cloudId, $account);
    }

    public function testSignParams()
    {
        $params = array(
            'access_key' => 'abcdefgh',
            'cloud_id' => '123456789',
            'timestamp' => '2011-03-01T15:39:10.260762Z',
        );
        $params = $this->signing->signParams('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $params['signature']
        );
    }

    public function testSignParamsWithoutAccessData()
    {
        $params = array('timestamp' => '2011-03-01T15:39:10.260762Z');
        $params = $this->signing->signParams('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $params['signature']
        );
    }

    public function testSignParamsWithoutTimestamp()
    {
        $params = $this->signing->signParams('GET', '/videos.json');

        $this->assertTrue(isset($params['timestamp']));
    }

    public function testSignature()
    {
        $params = array(
            'access_key' => 'abcdefgh',
            'cloud_id' => '123456789',
            'timestamp' => '2011-03-01T15:39:10.260762Z',
        );
        $signature = $this->signing->signature('GET', '/videos.json', $params);

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
        $signature = $this->signing->signature('GET', '/videos.json', $params);

        $this->assertEquals(
            'kVnZs/NX13ldKPdhFYoVnoclr8075DwiZF0TGgIbMsc=',
            $signature
        );
    }
}
