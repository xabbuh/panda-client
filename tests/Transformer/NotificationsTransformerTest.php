<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Transformer;

use Xabbuh\PandaClient\Model\Notifications;
use Xabbuh\PandaClient\Transformer\NotificationsTransformer;

/**
 * Test the NotificationsTransformer class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class NotificationsTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NotificationsTransformer
     */
    private $transformer;

    protected function setUp()
    {
        $this->transformer = new NotificationsTransformer();
    }

    /**
     * Test the transformation of a JSON encoded string into a Notifications
     * object.
     */
    public function testFromJSON()
    {
        $jsonString = '{
          "url": "http://www.example.com/notify",
          "events": {
            "video_created": false,
            "encoding_completed": false
          }
        }';
        $notifications = $this->transformer->stringToNotifications($jsonString);
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Notifications',
            get_class($notifications)
        );
        $this->assertEquals('http://www.example.com/notify', $notifications->getUrl());
        $videoCreatedEvent = $notifications->getNotificationEvent('video-created');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($videoCreatedEvent)
        );
        $this->assertFalse($videoCreatedEvent->isActive());
        $this->assertFalse($notifications->hasNotificationEvent('video-encoded'));
        $this->assertFalse($notifications->hasNotificationEvent('encoding-progress'));
        $encodingCompletedEvent = $notifications->getNotificationEvent('encoding-completed');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($encodingCompletedEvent)
        );
        $this->assertFalse($encodingCompletedEvent->isActive());

        return $notifications;
    }

    /**
     * Test the transformation of a JSON encoded string into a Notifications
     * object.
     */
    public function testFromJSONWithoutUrl()
    {
        $jsonString = '{
          "url": null,
          "events": {
            "video_created": false,
            "video_encoded": true,
            "encoding_progress": true,
            "encoding_completed": false
          }
        }';
        $notifications = $this->transformer->stringToNotifications($jsonString);
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\Notifications',
            get_class($notifications)
        );
        $this->assertNull($notifications->getUrl());
        $videoCreatedEvent = $notifications->getNotificationEvent('video-created');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($videoCreatedEvent)
        );
        $this->assertFalse($videoCreatedEvent->isActive());
        $videoEncodedEvent = $notifications->getNotificationEvent('video-encoded');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($videoEncodedEvent)
        );
        $this->assertTrue($videoEncodedEvent->isActive());
        $encodingProgressEvent = $notifications->getNotificationEvent('encoding-progress');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($encodingProgressEvent)
        );
        $this->assertTrue($encodingProgressEvent->isActive());
        $encodingCompletedEvent = $notifications->getNotificationEvent('encoding-completed');
        $this->assertEquals(
            'Xabbuh\PandaClient\Model\NotificationEvent',
            get_class($encodingCompletedEvent)
        );
        $this->assertFalse($encodingCompletedEvent->isActive());

        return $notifications;
    }

    /**
     * Test the transformation of a Notifications object into a
     * RequestParameterBag.
     *
     * @depends testFromJSON
     */
    public function testToRequestParams(Notifications $notifications)
    {
        $params = $this->transformer->toRequestParams($notifications);
        $this->assertEquals(
            'Symfony\Component\HttpFoundation\ParameterBag',
            get_class($params)
        );
        $this->assertEquals('http://www.example.com/notify', $params->get('url'));
        $this->assertEquals('false', $params->get('events[video_created]'));
        $this->assertFalse($params->has('events[video_encoded]'));
        $this->assertFalse($params->has('events[encoding_progress]'));
        $this->assertEquals('false', $params->get('events[encoding_completed]'));
    }

    /**
     * Test the transformation of a Notifications object without notification
     * url into a RequestParameterBag.
     *
     * @depends testFromJSONWithoutUrl
     */
    public function testToRequestParamsWithoutUrl(Notifications $notifications)
    {
        $params = $this->transformer->toRequestParams($notifications);
        $this->assertEquals(
            'Symfony\Component\HttpFoundation\ParameterBag',
            get_class($params)
        );
        $this->assertFalse($params->has('url'));
        $this->assertEquals('false', $params->get('events[video_created]'));
        $this->assertEquals('true', $params->get('events[video_encoded]'));
        $this->assertEquals('true', $params->get('events[encoding_progress]'));
        $this->assertEquals('false', $params->get('events[encoding_completed]'));
    }
}
