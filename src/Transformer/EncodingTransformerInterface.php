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
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface EncodingTransformerInterface
{
    /**
     * Transforms the serialized representation of an Encoding into an Encoding
     * object.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Encoding The transformed Encoding
     */
    public function stringToEncoding($jsonString);

    /**
     * Transforms the serialized representation of an Encoding collection into
     * an array of Encoding instances.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Encoding[] The transformed Encodings
     */
    public function stringToEncodingCollection($jsonString);
}
