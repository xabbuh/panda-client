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

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Xabbuh\PandaClient\Serializer\SerializerInterface;

/**
 * Plugs the Symfony Serializer component into the Panda client.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var SymfonySerializer
     */
    private $serializer;

    public function __construct(array $camelizedAttributes = array())
    {
        $normalizer = new Normalizer();
        $normalizer->setCamelizedAttributes($camelizedAttributes);
        $encoder = new JsonEncoder();

        $this->serializer = new SymfonySerializer(array($normalizer), array($encoder));
    }

    /**
     * {@inheritDoc}
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * {@inheritDoc}
     */
    public function deserialize($data, $type)
    {
        $fqcn = 'Xabbuh\PandaClient\Model\\'.$type;

        return $this->serializer->deserialize($data, $fqcn, 'json');
    }

    /**
     * {@inheritDoc}
     */
    public static function getCloudSerializer()
    {
        return new Serializer(array(
            's3_videos_bucket',
            's3_private_access',
            'created_at',
            'updated_at',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public static function getEncodingSerializer()
    {
        return new Serializer(array(
            'video_id',
            'profile_id',
            'profile_name',
            'encoding_progress',
            'started_encoding_at',
            'encoding_time',
            'created_at',
            'updated_at',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public static function getProfileSerializer()
    {
        return new Serializer(array(
            'aspect_mode',
            'video_bitrate',
            'audio_bitrate',
            'created_at',
            'updated_at',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public static function getVideoSerializer()
    {
        return new Serializer(array(
            'original_filename',
            'source_url',
            'video_codec',
            'audio_codec',
            'file_size',
            'created_at',
            'updated_at',
        ));
    }
}
