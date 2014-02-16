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

use Xabbuh\PandaClient\Model\Profile;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ProfileTransformerInterface
{
    /**
     * Transforms the serialized representation of a profile into a Profile object.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Profile The transformed Profile
     */
    public function stringToProfile($jsonString);

    /**
     * Transforms the serialized representation of a collection of profiles into
     * an array of Profile objects.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Profile[] The transformed Profiles
     */
    public function stringToProfileCollection($jsonString);

    /**
     * Transforms a Profile object into a ParameterBag of request parameters.
     *
     * @param Profile $profile The Profile instance to transform
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag The request parameters
     */
    public function toRequestParams(Profile $profile);
}
