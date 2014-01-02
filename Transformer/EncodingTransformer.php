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
        return $this->fromObject(json_decode($jsonString));
    }

    /**
     * {@inheritDoc}
     */
    public function fromJSONCollection($jsonString)
    {
        $json = json_decode($jsonString);
        $encodings = array();
        foreach ($json as $object) {
            $encodings[] = $this->fromObject($object);
        }
        return $encodings;
    }

    /**
     * {@inheritDoc}
     */
    private function fromObject(\stdClass $object)
    {
        $encoding = new Encoding();
        $this->setModelProperties($encoding, $object);
        return $encoding;
    }
}
