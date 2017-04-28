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

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Api\AccountManager;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AccountManagerTest extends TestCase
{
    public function testRegisterAccount()
    {
        $account = new Account('', '', '');
        $defaultAccount = new Account('', '', '');
        $accountManager = new AccountManager();
        $accountManager->setDefaultAccount('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->registerAccount('default-key', $defaultAccount);

        $this->assertSame($account, $accountManager->getAccount('the-key'));
        $this->assertSame($defaultAccount, $accountManager->getAccount('default-key'));
        $this->assertSame($defaultAccount, $accountManager->getDefaultAccount());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNonExistingDefaultAccount()
    {
        $account = new Account('', '', '');
        $accountManager = new AccountManager('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->getDefaultAccount();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNonExistingDefaultAccountWithEmptyAccountManager()
    {
        $accountManager = new AccountManager('default-key');
        $accountManager->getDefaultAccount();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNonExistingAccount()
    {
        $account = new Account('', '', '');
        $accountManager = new AccountManager('default-key');
        $accountManager->registerAccount('the-key', $account);
        $accountManager->getAccount('another-key');
    }
}
