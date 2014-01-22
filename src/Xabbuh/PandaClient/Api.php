<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Api\AccountManager;
use Xabbuh\PandaClient\Api\Cloud;
use Xabbuh\PandaClient\Api\CloudManager;
use Xabbuh\PandaClient\Api\HttpClient;
use Xabbuh\PandaClient\Transformer\CloudTransformer;
use Xabbuh\PandaClient\Transformer\EncodingTransformer;
use Xabbuh\PandaClient\Transformer\NotificationsTransformer;
use Xabbuh\PandaClient\Transformer\ProfileTransformer;
use Xabbuh\PandaClient\Transformer\TransformerRegistry;
use Xabbuh\PandaClient\Transformer\TransformerRegistryInterface;
use Xabbuh\PandaClient\Transformer\VideoTransformer;

/**
 * Simple entry point to the Panda API client.
 *
 * Basically, you receive an API instance by passing your cloud configuration
 * to the static getInstance() method. Your configuration should look like
 * this:
 *
 * - accounts:
 *   - an identifier
 *     - access_key: your access key
 *     - secret_key: your secret key
 *     - api_host: the API host to use
 * - clouds:
 *   - an identifier
 *     - id: your cloud id
 *     - account: one of your account identifiers as configured above
 *
 * A sample config can look like this:
 *
 * <code>
 * $config = array(
 *     'accounts' => array(
 *         'default' => array(
 *             'access_key' => ...,
 *             'secret_key' => ...,
 *             'api_host'   => 'api.pandastream.com',
 *         ),
 *     ),
 *     'clouds' => array(
 *         'default' => array(
 *             'id'      => ...,
 *             'account' => 'default',
 *         ),
 *     ),
 * );
 * </code>
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Api
{
    /**
     * @var TransformerRegistryInterface
     */
    private $transformers;

    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * @var CloudManager
     */
    private $cloudManager;

    /**
     * @param TransformerRegistryInterface $transformers
     *
     * @return Api
     */
    public function setTransformers(TransformerRegistryInterface $transformers)
    {
        $this->transformers = $transformers;
        return $this;
    }

    /**
     * @return TransformerRegistryInterface
     */
    public function getTransformers()
    {
        return $this->transformers;
    }

    /**
     * @param AccountManager $accountManager
     *
     * @return Api
     */
    public function setAccountManager(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
        return $this;
    }

    /**
     * @return AccountManager
     */
    public function getAccountManager()
    {
        return $this->accountManager;
    }

    /**
     * @param CloudManager $cloudManager
     *
     * @return Api
     */
    public function setCloudManager(CloudManager $cloudManager)
    {
        $this->cloudManager = $cloudManager;
        return $this;
    }

    /**
     * @return CloudManager
     */
    public function getCloudManager()
    {
        return $this->cloudManager;
    }

    /**
     * @param array $config
     *
     * @return Api
     *
     * @throws \InvalidArgumentException if the given configuration is invalid
     */
    public static function getInstance(array $config)
    {
        // register model transformers
        $serializer = new Serializer(
            array(new GetSetMethodNormalizer()),
            array(new JsonEncoder())
        );
        $transformers = new TransformerRegistry();
        $transformers->setCloudTransformer(new CloudTransformer($serializer));
        $transformers->setEncodingTransformer(new EncodingTransformer());
        $transformers->setNotificationsTransformer(new NotificationsTransformer());
        $transformers->setProfileTransformer(new ProfileTransformer());
        $transformers->setVideoTransformer(new VideoTransformer());

        // register the accounts
        $accountManager = new AccountManager(
            isset($config['default_account']) ? $config['default_account'] : 'default'
        );

        if (!isset($config['accounts']) || !is_array($config['accounts'])) {
            throw new \InvalidArgumentException('No account configuration given.');
        }

        foreach ($config['accounts'] as $name => $cloudConfig) {
            foreach (array('access_key', 'secret_key', 'api_host') as $option) {
                if (!isset($cloudConfig[$option])) {
                    throw new \InvalidArgumentException(
                        sprintf('Missing option %s for account %s', $option, $name)
                    );
                }
            }

            $accountManager->registerAccount(
                $name,
                new Account(
                    $cloudConfig['access_key'],
                    $cloudConfig['secret_key'],
                    $cloudConfig['api_host']
                )
            );
        }

        // register the clouds
        $cloudManager = new CloudManager(
            isset($config['default_cloud']) ? $config['default_cloud'] : 'default'
        );

        if (!isset($config['clouds']) || !is_array($config['clouds'])) {
            throw new \InvalidArgumentException('No cloud configuration given.');
        }

        foreach ($config['clouds'] as $name => $cloudConfig) {
            foreach (array('id', 'account') as $option) {
                if (!isset($cloudConfig[$option])) {
                    throw new \InvalidArgumentException(
                        sprintf('Missing option %s for cloud %s', $option, $name)
                    );
                }
            }

            try {
                $account = $accountManager->getAccount($cloudConfig['account']);
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException(
                    sprintf('Invalid account %s for cloud %s', $name, $cloudConfig['account'])
                );
            }

            $httpClient = new HttpClient($cloudConfig['id'], $account);
            $cloudManager->registerCloud(
                $name,
                new Cloud($httpClient, $transformers)
            );
        }

        $api = new Api();
        $api->setTransformers($transformers)
            ->setAccountManager($accountManager)
            ->setCloudManager($cloudManager)
        ;

        return $api;
    }

    /**
     * @param string $name
     *
     * @return Cloud
     */
    public function getCloud($name)
    {
        return $this->cloudManager->getCloud($name);
    }
}
