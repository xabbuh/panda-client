<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Api;

use Xabbuh\PandaClient\Api\Account;

/**
 * Test the Account class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AccountTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the Account's getter methods.
     */
    public function testGetter()
    {
        $account = new Account('access-key', 'secret-key', 'api-host');
        $this->assertEquals('access-key', $account->getAccessKey());
        $this->assertEquals('secret-key', $account->getSecretKey());
        $this->assertEquals('api-host', $account->getApiHost());
    }
}
