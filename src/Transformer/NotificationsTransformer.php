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
use Xabbuh\PandaClient\Model\NotificationEvent;
use Xabbuh\PandaClient\Model\Notifications;

/**
 * Transformation from various data representation formats into Notifications
 * model objects and vice versa.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class NotificationsTransformer extends BaseTransformer implements NotificationsTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function stringToNotifications($jsonString)
    {
        $json = json_decode($jsonString);
        $notifications = new Notifications();

        if (isset($json->url)) {
            $notifications->setUrl($json->url);
        }

        if (isset($json->events->video_created)) {
            $notifications->addNotificationEvent(
                new NotificationEvent('video_created', $json->events->video_created));
        }

        if (isset($json->events->video_encoded)) {
            $notifications->addNotificationEvent(
                new NotificationEvent('video_encoded', $json->events->video_encoded));
        }

        if (isset($json->events->encoding_progress)) {
            $notifications->addNotificationEvent(
                new NotificationEvent('encoding_progress', $json->events->encoding_progress));
        }

        if (isset($json->events->encoding_completed)) {
            $notifications->addNotificationEvent(
                new NotificationEvent('encoding_completed', $json->events->encoding_completed));
        }

        return $notifications;
    }

    /**
     * {@inheritDoc}
     */
    public function toRequestParams(Notifications $notifications)
    {
        $params = new ParameterBag();

        if (null !== $notifications->getUrl()) {
            $params->set('url', $notifications->getUrl());
        }

        if ($notifications->hasNotificationEvent('video-created')) {
            $this->addRequestParamForEvent($notifications, 'video_created', $params);
        }

        if ($notifications->hasNotificationEvent('video-encoded')) {
            $this->addRequestParamForEvent($notifications, 'video_encoded', $params);
        }

        if ($notifications->hasNotificationEvent('encoding-progress')) {
            $this->addRequestParamForEvent($notifications, 'encoding_progress', $params);
        }

        if ($notifications->hasNotificationEvent('encoding-completed')) {
            $this->addRequestParamForEvent($notifications, 'encoding_completed', $params);
        }

        return $params;
    }

    private function addRequestParamForEvent(Notifications $notifications, $eventName, ParameterBag $params)
    {
        $event = $notifications->getNotificationEvent($eventName);

        if ($event->isActive()) {
            $params->set('events['.$eventName.']', 'true');
        } else {
            $params->set('events['.$eventName.']', 'false');
        }
    }
}
