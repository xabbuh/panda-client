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

use Xabbuh\PandaClient\Model\Cloud;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface CloudTransformerInterface
{
    /**
     * Transform a JSON encoded string into a Cloud model object.
     *
     * @param string $jsonString The JSON encoded string being
     *
     * @return Cloud The generated Cloud
     */
    public function fromJSON($jsonString);
}
 