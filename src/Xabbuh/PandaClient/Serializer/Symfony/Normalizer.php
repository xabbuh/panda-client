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

use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Extends the Symfony GetSetMethodNormalizer to be able to denormalize data
 * collections.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Normalizer extends GetSetMethodNormalizer
{
    /**
     * {@inheritDoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        // prematurely return in case of an empty collection
        if (is_array($data) && count($data) == 0) {
            return array();
        }

        // check if the data is a collection
        if (isset($data[0]) && is_array($data[0])) {
            $collection = array();

            foreach ($data as &$item) {
                $collection[] = $this->doDenormalize($item, $class, $format, $context);
            }

            return $collection;
        }

        return $this->doDenormalize($data, $class, $format, $context);
    }

    private function doDenormalize($data, $class)
    {
        $object = new $class;

        foreach ($data as $attribute => $value) {
            $setter = 'set'.$this->formatAttribute($attribute);

            if (method_exists($object, $setter)) {
                $object->$setter($value);
            } else {
                throw new \InvalidArgumentException(
                    'Denormalization error: Unknown attribute '.$attribute.' in model'
                );
            }
        }

        return $object;
    }
}
