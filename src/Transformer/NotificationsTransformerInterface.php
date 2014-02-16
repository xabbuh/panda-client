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

use Xabbuh\PandaClient\Model\Notifications;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface NotificationsTransformerInterface
{
    /**
     * Transforms serialized representation of notifications into a Notifications
     * model object.
     *
     * @param string $jsonString The string in json format being transformed
     *
     * @return Notifications The notifications
     */
    public function stringToNotifications($jsonString);

    /**
     * Transform a Notifications object into a ParameterBag of request parameters.
     *
     * @param Notifications $notifications The notifications to transform
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag The request parameters
     */
    public function toRequestParams(Notifications $notifications);
}
