<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Serializer;

/**
 * Interface definition for serializer.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface SerializerInterface
{
    /**
     * Serializes a model object into a JSON string.
     *
     * @param mixed $data The data to serialize
     *
     * @return string The serialized data as a JSON string
     */
    public function serialize($data);

    /**
     * Deseriarilzes data into the given type.
     *
     * @param string $data The data to deserialize
     * @param string $type The result data type (must be one of the model classes
     *                     from the Xabbuh\PandaClient\Model namespace
     *
     * @return mixed The deserialized model object
     */
    public function deserialize($data, $type);

    /**
     * Returns a serializer for Cloud models.
     *
     * @return SerializerInterface
     */
    public static function getCloudSerializer();

    /**
     * Returns a serializer for Encoding models.
     *
     * @return SerializerInterface
     */
    public static function getEncodingSerializer();

    /**
     * Returns a serializer for Profile models.
     *
     * @return SerializerInterface
     */
    public static function getProfileSerializer();

    /**
     * Returns a serializer for Video models.
     *
     * @return SerializerInterface
     */
    public static function getVideoSerializer();
}
