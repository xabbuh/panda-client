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
 * Transform various data representation formats into profiles and vice versa.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ProfileTransformer extends BaseTransformer implements ProfileTransformerInterface
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
        $profiles = array();
        foreach ($json as $object) {
            $profiles[] = $this->fromObject($object);
        }
        return $profiles;
    }

    /**
     * Transform a standard php object into a Profile instance
     *
     * @param \stdClass $object The object being transformed
     *
     * @return Profile The transformed profile
     */
    private function fromObject(\stdClass $object)
    {
        $profile = new Profile();
        $this->setModelProperties($profile, $object);
        return $profile;
    }
}
