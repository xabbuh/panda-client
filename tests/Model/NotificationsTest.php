<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Model;

use Xabbuh\PandaClient\Model\NotificationEvent;
use Xabbuh\PandaClient\Model\Notifications;

/**
 * Test the Notifications model class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class NotificationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting and getting the notification url.
     */
    public function testUrl()
    {
        $notifications = new Notifications();
        $this->assertNull($notifications->getUrl());
        $notifications->setUrl("http://www.example.com/notify");
        $this->assertEquals("http://www.example.com/notify", $notifications->getUrl());
    }

    /**
     * Test if adding, retrieving and removing events works properly.
     */
    public function testEvents()
    {
        $notifications = new Notifications();
        $videoCreatedEvent = new NotificationEvent("video-created", true);
        $notifications->addNotificationEvent($videoCreatedEvent);
        $this->assertEquals(
            $videoCreatedEvent,
            $notifications->getNotificationEvent("video-created")
        );
        $videoCreatedEvent2 = new NotificationEvent("video-created", true);
        $this->assertEquals(
            $videoCreatedEvent2,
            $notifications->getNotificationEvent("video-created")
        );
        try {
            $notifications->getNotificationEvent("video-encoded");
        } catch (\InvalidArgumentException $e) {
            try {
                $notifications->removeNotificationEvent($videoCreatedEvent2);
                $notifications->getNotificationEvent("video-created");
            } catch (\InvalidArgumentException $e) {
                return ;
            }
        }
        $this->fail("Expected InvalidArgumentException to be thrown.");
    }

    /**
     * Test that event names are normalised when requested.
     */
    public function testEventNameNormalisation()
    {
        $notifications = new Notifications();
        $videoCreatedEvent = new NotificationEvent("video-created", true);
        $notifications->addNotificationEvent($videoCreatedEvent);
        $this->assertEquals(
            $videoCreatedEvent,
            $notifications->getNotificationEvent("video_created")
        );
    }

    /**
     * Test that an exception is thrown if a requested event is not registered.
     */
    public function testGetNonExistingEvent()
    {
        $notifications = new Notifications();
        $this->setExpectedException("InvalidArgumentException");
        $notifications->getNotificationEvent("video_created");
    }
}
