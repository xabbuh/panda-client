<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Model;

/**
 * Representation of notification events consisting of an event name and its
 * activation state.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class NotificationEvent
{
    private $event;
    
    private $active;
    
    public function __construct($event, $active)
    {
        // normalise event names
        $event = strtr($event, '_', '-');

        // check for valid event names
        if (
            $event != 'video-created' && $event != 'video-encoded' &&
            $event != 'encoding-progress' && $event != 'encoding-completed'
        ) {
            throw new \InvalidArgumentException('Unknown notification event $event');
        }

        $this->event = $event;
        $this->active = $active;
    }
    
    public function getEvent()
    {
        return $this->event;
    }
    
    public function isActive()
    {
        return $this->active;
    }
}
