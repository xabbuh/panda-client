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

/**
 * Factory for retrieving model transformers.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TransformerFactory
{
    /**
     * The generated transformers
     * 
     * @var array
     */
    private $transformers = array();


    /**
     * Registers a transformer for a model. Replaces an already registered
     * transformer.
     *
     * @param string $model     The name of the model class (without namespaces)
     * @param string $className The transformer to register
     */
    public function registerTransformer($model, $className)
    {
        $transformer = new $className();
        $this->transformers[$model] = $transformer;
    }
    
    /**
     * Returns the transformer for a model.
     *
     * @param string $model The model for which the transformer instance is returned
     *
     * @return BaseTransformer The transformer instance
     *
     * @throws \InvalidArgumentException if no transformer is registered for the model
     */
    public function get($model)
    {
        if (!isset($this->transformers[$model])) {
            throw new \InvalidArgumentException('No transformer for class $model registered');
        }
        return $this->transformers[$model];
    }
}
