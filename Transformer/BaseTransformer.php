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

/**
 * Common functions for all transformer classes.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class BaseTransformer
{
    /**
     * Get the getter or setter method name for a particular property.
     *
     * @param string $propertyName The property name in underscore style
     *
     * @return string The generated method name in camel case style
     */
    protected function getMethodName($propertyName)
    {
        $methodName = 'set';

        while (($pos = strpos($propertyName, '_')) !== false) {
            $tmp = substr($propertyName, 0, $pos);
            $tmp .= ucfirst(substr($propertyName, $pos + 1));
            $propertyName = $tmp;
        }
        return $methodName . ucfirst($propertyName);
    }

    /**
     * Read the properties from one object and set the properties of the
     * model respectively.
     *
     * @param ModelInterface $model  The model which properties are being set
     * @param \stdClass      $object The object from where the property values are being read
     *
     * @throws \InvalidArgumentException if a property of $object doesn't exist in $model
     */
    protected function setModelProperties(ModelInterface $model, \stdClass $object)
    {
        foreach ($object as $name => $value) {
            $methodName = $this->getMethodName($name);
            if (method_exists($model, $methodName)) {
                $model->$methodName($value);
            } else {
                throw new \InvalidArgumentException(
                    'Transformation error: Unknown property '.$name.' in model'
                );
            }
        }
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
