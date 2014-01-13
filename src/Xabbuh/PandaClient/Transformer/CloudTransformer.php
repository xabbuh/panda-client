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
 * Transform a cloud from and to different data formats.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudTransformer extends BaseTransformer implements CloudTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function fromJSON($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Cloud');
    }
}
