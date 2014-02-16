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
 * Manager of all accounts which can be used in the application.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AccountManager implements AccountManagerInterface
{
    /**
     * The default account's configuration key
     * @var string
     */
    private $defaultAccountKey;

    /**
     * The accounts being managed
     * @var Account[]
     */
    private $accounts = array();

    /**
     * {@inheritDoc}
     */
    public function registerAccount($key, Account $account)
    {
        $this->accounts[$key] = $account;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccount($key)
    {
        return isset($this->accounts[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccount($key = null)
    {
        if (null === $key) {
            $key = $this->defaultAccountKey;
        }

        if (!$this->hasAccount($key)) {
            throw new \InvalidArgumentException('No account for key '.$key.' configured.');
        }

        return $this->accounts[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultAccount($key)
    {
        $this->defaultAccountKey = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultAccount()
    {
        return $this->getAccount($this->defaultAccountKey);
    }
}
