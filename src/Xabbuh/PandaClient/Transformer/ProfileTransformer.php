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
use Xabbuh\PandaClient\Model\Profile;

/**
 * Transform various data representation formats into profiles and vice versa.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ProfileTransformer extends BaseTransformer implements ProfileTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function fromJSON($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Profile');
    }

    /**
     * {@inheritDoc}
     */
    public function fromJSONCollection($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Profile');
    }

    /**
     * {@inheritDoc}
     */
    public function toRequestParams(Profile $object)
    {
        $params = new ParameterBag();

        foreach (get_class_methods(get_class($object)) as $method) {
            if ('get' === substr($method, 0, 3)) {
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
