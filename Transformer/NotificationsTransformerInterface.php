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

use Symfony\Component\HttpFoundation\ParameterBag;
use Xabbuh\PandaClient\Model\ModelInterface;
use Xabbuh\PandaClient\Model\Notifications;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface NotificationsTransformerInterface
{
    /**
     * Transform the JSON representation of notifications into a Notifications
     * model object.
     *
     * @param string $jsonString The string in json format being transformed
     *
     * @return Notifications The notifications
     */
    public function fromJSON($jsonString);

    /**
     * Transform a Notifications object into a ParameterBag of request parameters.
     *
     * @param ModelInterface $notifications The notifications to transform
     *
     * @return ParameterBag The request parameters
     */
    public function toRequestParams(ModelInterface $notifications);
}
 