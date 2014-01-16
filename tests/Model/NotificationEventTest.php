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

/**
 * Test the NotificationEvent model class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class NotificationEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the video-created event.
     */
    public function testVideoCreatedEvent()
    {
        $event = new NotificationEvent("video-created", true);
        $this->assertEquals("video-created", $event->getEvent());
        $this->assertTrue($event->isActive());
    }

    /**
     * Test the video-encoded event.
     */
    public function testVideoEncodedEvent()
    {
        $event = new NotificationEvent("video-encoded", false);
        $this->assertEquals("video-encoded", $event->getEvent());
        $this->assertFalse($event->isActive());
    }

    /**
     * Test the encoding-progress event.
     */
    public function testEncodingProgressEvent()
    {
        $event = new NotificationEvent("encoding-progress", true);
        $this->assertEquals("encoding-progress", $event->getEvent());
        $this->assertTrue($event->isActive());
    }

    /**
     * Test the encoding-completed event.
     */
    public function testEncodingCompletedEvent()
    {
        $event = new NotificationEvent("encoding-completed", false);
        $this->assertEquals("encoding-completed", $event->getEvent());
        $this->assertFalse($event->isActive());
    }

    /**
     * Test underscored event names normalisation.
     */
    public function testUnderscoreEventNames()
    {
        $event = new NotificationEvent("video_created", false);
        $this->assertEquals("video-created", $event->getEvent());

        $event = new NotificationEvent("video_encoded", false);
        $this->assertEquals("video-encoded", $event->getEvent());

        $event = new NotificationEvent("encoding_progress", false);
        $this->assertEquals("encoding-progress", $event->getEvent());

        $event = new NotificationEvent("encoding_completed", false);
        $this->assertEquals("encoding-completed", $event->getEvent());
    }

    /**
     * Test handling of invalid event names.
     */
    public function testInvalidEvent()
    {
        $this->setExpectedException("InvalidArgumentException");

        new NotificationEvent("invalid-event", true);
    }
}
