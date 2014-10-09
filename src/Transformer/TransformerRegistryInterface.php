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
 * Registry for model transformers.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface TransformerRegistryInterface
{
    /**
     * @param CloudTransformerInterface $transformer
     */
    public function setCloudTransformer(CloudTransformerInterface $transformer);

    /**
     * @return CloudTransformerInterface
     */
    public function getCloudTransformer();

    /**
     * @param EncodingTransformerInterface $transformer
     */
    public function setEncodingTransformer(EncodingTransformerInterface $transformer);

    /**
     * @return EncodingTransformerInterface
     */
    public function getEncodingTransformer();

    /**
     * @param NotificationsTransformerInterface $transformer
     */
    public function setNotificationsTransformer(NotificationsTransformerInterface $transformer);

    /**
     * @return NotificationsTransformerInterface
     */
    public function getNotificationsTransformer();

    /**
     * @param ProfileTransformerInterface $transformer
     */
    public function setProfileTransformer(ProfileTransformerInterface $transformer);

    /**
     * @return ProfileTransformerInterface
     */
    public function getProfileTransformer();

    /**
     * @param VideoTransformerInterface $transformer
     */
    public function setVideoTransformer(VideoTransformerInterface $transformer);

    /**
     * @return VideoTransformerInterface
     */
    public function getVideoTransformer();
}
