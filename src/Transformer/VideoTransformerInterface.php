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

use Xabbuh\PandaClient\Model\Video;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface VideoTransformerInterface
{
    /**
     * Transform the JSON representation of a video into a Video model object.
     *
     * @param string $jsonString JSON representation of a video
     *
     * @return Video The generated model object
     */
    public function fromJSON($jsonString);

    /**
     * Transform a JSON representation of a collection of videos into an array
     * of Video objects.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Video[] The transformed Videos
     */
    public function fromJSONCollection($jsonString);
}
