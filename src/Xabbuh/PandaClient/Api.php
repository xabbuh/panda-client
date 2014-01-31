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

use Xabbuh\PandaClient\Api\AccountManager;
use Xabbuh\PandaClient\Api\Cloud;
use Xabbuh\PandaClient\Api\CloudManager;
use Xabbuh\PandaClient\Api\HttpClient;
use Xabbuh\PandaClient\Serializer\Symfony\Serializer;
use Xabbuh\PandaClient\Transformer\CloudTransformer;
use Xabbuh\PandaClient\Transformer\EncodingTransformer;
use Xabbuh\PandaClient\Transformer\NotificationsTransformer;
use Xabbuh\PandaClient\Transformer\ProfileTransformer;
use Xabbuh\PandaClient\Transformer\TransformerRegistry;
use Xabbuh\PandaClient\Transformer\VideoTransformer;

/**
 * Implementation of the algorithm described in {@link AbstractApi} to initialize
 * an Api object to access the Panda Encoding API.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Api extends AbstractApi
{
    /**
     * {@inheritDoc}
     */
    protected function createAccountManager()
    {
        return new AccountManager();
    }

    /**
     * {@inheritDoc}
     */
    protected function createCloudManager()
    {
        return new CloudManager();
    }

    /**
     * {@inheritDoc}
     */
    protected function createHttpClient()
    {
        return new HttpClient();
    }

    /**
     * {@inheritDoc}
     */
    protected function createCloud()
    {
        return new Cloud();
    }

    /**
     * {@inheritDoc}
     */
    protected function createTransformerRegistry()
    {
        return new TransformerRegistry();
    }

    /**
     * {@inheritDoc}
     */
    protected function createCloudTransformer()
    {
        $transformer = new CloudTransformer();
        $transformer->setSerializer(Serializer::getCloudSerializer());

        return $transformer;
    }

    /**
     * {@inheritDoc}
     */
    protected function createEncodingTransformer()
    {
        $transformer = new EncodingTransformer();
        $transformer->setSerializer(Serializer::getEncodingSerializer());

        return $transformer;
    }

    /**
     * {@inheritDoc}
     */
    protected function createNotificationsTransformer()
    {
        return new NotificationsTransformer();
    }

    /**
     * {@inheritDoc}
     */
    protected function createProfileTransformer()
    {
        $transformer = new ProfileTransformer();
        $transformer->setSerializer(Serializer::getProfileSerializer());

        return $transformer;
    }

    /**
     * {@inheritDoc}
     */
    protected function createVideoTransformer()
    {
        $transformer = new VideoTransformer();
        $transformer->setSerializer(Serializer::getVideoSerializer());

        return $transformer;
    }
}
