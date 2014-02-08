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
 *
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface ProfileTransformerInterface
{
    /**
     * Transform a JSON representation of a profile into a Profile object.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Profile The transformed Profile
     */
    public function fromJSON($jsonString);

    /**
     * Transform a JSON representation of a collection of profiles into an
     * array of Profile objects.
     *
     * @param string $jsonString The JSON string being transformed
     *
     * @return Profile[] The transformed Profiles
     */
    public function fromJSONCollection($jsonString);

    /**
     * Transform a Notifications object into a ParameterBag of request parameters.
     *
     * @param Profile $notifications The notifications to transform
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag The request parameters
     */
    public function toRequestParams(Profile $notifications);
}
