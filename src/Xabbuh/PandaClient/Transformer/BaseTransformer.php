<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Transformer;

use Symfony\Component\HttpFoundation\ParameterBag;
use Xabbuh\PandaClient\Model\ModelInterface;
use Xabbuh\PandaClient\Serializer\SerializerInterface;

/**
 * Common functions for all transformer classes.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class BaseTransformer
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * {@inheritDoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
