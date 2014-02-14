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
 * Transformation from various data representation formats into Video models and
 * vice versa.
 *
 * A JSON object returned by the Panda api service may contain the following
 * properties:
 * <ul>
 *   <li>id</li>
 *   <li>created_at</li>
 *   <li>updated_at</li>
 *   <li>original_filename</li>
 *   <li>extname</li>
 *   <li>source_url</li>
 *   <li>duration</li>
 *   <li>width</li>
 *   <li>height</li>
 *   <li>file_size</li>
 *   <li>video_bitrate</li>
 *   <li>audio_bitrate</li>
 *   <li>video_codec</li>
 *   <li>audio_codec</li>
 *   <li>fps</li>
 *   <li>audio_channels</li>
 *   <li>audio_sample_rate</li>
 *   <li>status</li>
 *   <li>mime_type</li>
 *   <li>path</li>
 * </ul>
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class VideoTransformer extends BaseTransformer implements VideoTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function fromJSON($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Video');
    }

    /**
     * {@inheritDoc}
     */
    public function fromJSONCollection($jsonString)
    {
        return $this->serializer->deserialize($jsonString, 'Video');
    }
}
