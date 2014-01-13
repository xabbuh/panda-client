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

    /**
     * Convert a php object into a parameter bag which can be used for http requests/responses.
     *
     * @param ModelInterface $object The object being converted
     *
     * @return ParameterBag The request parameters
     */
    public function toRequestParams(ModelInterface $object)
    {
        $params = new ParameterBag();
        foreach (get_class_methods(get_class($object)) as $method) {
            if (substr($method, 0, 3) == 'get') {
                $property = strtolower(preg_replace(
                    '/([a-z])([A-Z])/',
                    '\$1_\$2',
                    substr($method, 3)
                ));
                $params->set($property, $object->$method());
            }
        }

        return $params;
    }
}
