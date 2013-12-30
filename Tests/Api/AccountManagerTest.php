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
use Xabbuh\PandaClient\Api\AccountManager;

/**
 * Test the AccountManager class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AccountManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterAccount()
    {
        $mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Account');
        $mockBuilder->disableOriginalConstructor();

        /** @var Account $account */
        $account = $mockBuilder->getMock();

        /** @var Account $defaultAccount */
        $defaultAccount = $mockBuilder->getMock();

        $accountManager = new AccountManager('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->registerAccount('default-key', $defaultAccount);

        $this->assertSame($account, $accountManager->getAccount('the-key'));
        $this->assertSame($defaultAccount, $accountManager->getAccount('default-key'));
        $this->assertSame($defaultAccount, $accountManager->getDefaultAccount());
    }

    public function testGetNonExistingDefaultAccount()
    {
        $mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Account');
        $mockBuilder->disableOriginalConstructor();

        /** @var Account $account */
        $account = $mockBuilder->getMock();

        $this->setExpectedException('InvalidArgumentException');
        $accountManager = new AccountManager('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->getDefaultAccount();
    }

    public function testGetNonExistingDefaultAccountWithEmptyAccountManager()
    {
        $this->setExpectedException('InvalidArgumentException');
        $accountManager = new AccountManager('default-key');
        $accountManager->getDefaultAccount();
    }

    public function testGetNonExistingAccount()
    {
        $mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Account');
        $mockBuilder->disableOriginalConstructor();

        /** @var Account $account */
        $account = $mockBuilder->getMock();

        $this->setExpectedException('InvalidArgumentException');
        $accountManager = new AccountManager('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->getAccount('another-key');
    }
}
