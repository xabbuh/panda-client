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
 * Interface definition for account manager implementations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface AccountManagerInterface
{
    /**
     * Register an Account on this manager.
     *
     * @param string  $key     Map this key to the Account being registered
     * @param Account $account Account to register
     */
    public function registerAccount($key, Account $account);

    /**
     * Returns the Account for a key.
     *
     * @param string $key The internal key
     *
     * @return Account The requested Account
     *
     * @throws \InvalidArgumentException if no account for the given key exists
     */
    public function getAccount($key);

    /**
     * Returns the application's default account.
     *
     * @return Account
     */
    public function getDefaultAccount();
}
