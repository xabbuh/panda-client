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
 * Registry for the model transformers.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TransformerRegistry implements TransformerRegistryInterface
{
    /**
     * The generated transformers
     * 
     * @var array
     */
    private $transformers = array();

    private function set($class, $transformer)
    {
        $this->transformers[$class] = $transformer;
    }

    private function get($class)
    {
        if (!isset($this->transformers[$class])) {
            throw new \InvalidArgumentException(
                'No transformer configured for model '.$class
            );
        }

        return $this->transformers[$class];
    }

    /**
     * {@inheritDoc}
     */
    public function setCloudTransformer(CloudTransformerInterface $transformer)
    {
        $this->set('Cloud', $transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function getCloudTransformer()
    {
        return $this->get('Cloud');
    }

    /**
     * {@inheritDoc}
     */
    public function setEncodingTransformer(EncodingTransformerInterface $transformer)
    {
        $this->set('Encoding', $transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function getEncodingTransformer()
    {
        return $this->get('Encoding');
    }

    /**
     * {@inheritDoc}
     */
    public function setNotificationsTransformer(NotificationsTransformerInterface $transformer)
    {
        $this->set('Notifications', $transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function getNotificationsTransformer()
    {
        return $this->get('Notifications');
    }

    /**
     * {@inheritDoc}
     */
    public function setProfileTransformer(ProfileTransformerInterface $transformer)
    {
        $this->set('Profile', $transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileTransformer()
    {
        return $this->get('Profile');
    }

    /**
     * {@inheritDoc}
     */
    public function setVideoTransformer(VideoTransformerInterface $transformer)
    {
        $this->set('Video', $transformer);
    }

    /**
     * {@inheritDoc}
     */
    public function getVideoTransformer()
    {
        return $this->get('Video');
    }
}
