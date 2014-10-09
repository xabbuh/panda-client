<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Serializer\Symfony;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Xabbuh\PandaClient\Serializer\SerializerInterface;

/**
 * Plugs the Symfony Serializer component into the Panda client.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    private $serializer;

    public function __construct()
    {
        $this->serializer = new SymfonySerializer(
            array(new Normalizer()), array(new JsonEncoder())
        );
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize($data, $type)
    {
        $fqcn = 'Xabbuh\PandaClient\Model\\'.$type;

        return $this->serializer->deserialize($data, $fqcn, 'json');
    }

    /**
     * {@inheritDoc}
     */
    public static function getCloudSerializer()
    {
        return new Serializer();
    }

    /**
     * {@inheritDoc}
     */
    public static function getEncodingSerializer()
    {
        return new Serializer();
    }

    /**
     * {@inheritDoc}
     */
    public static function getProfileSerializer()
    {
        return new Serializer();
    }

    /**
     * {@inheritDoc}
     */
    public static function getVideoSerializer()
    {
        return new Serializer();
    }
}
