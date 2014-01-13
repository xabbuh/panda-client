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

use Xabbuh\PandaClient\Model\Encoding;

/**
 * Transform Encodings from and to various data representation formats.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class EncodingTransformer extends BaseTransformer implements EncodingTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function fromJSON($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Encoding');
    }

    /**
     * {@inheritDoc}
     */
    public function fromJSONCollection($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Encoding');
    }
}
