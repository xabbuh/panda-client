<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Api;

use Xabbuh\PandaClient\Api\Cloud;
use Xabbuh\PandaClient\Api\CloudManager;

/**
 * Tests the CloudManager class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $mockBuilder;

    /**
     * @var CloudManager
     */
    private $cloudManager;

    protected function setUp()
    {
        $this->mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Cloud');
        $this->mockBuilder->disableOriginalConstructor();

        $this->cloudManager = new CloudManager('default-key');
    }

    public function testRegisterCloud()
    {
        /** @var Cloud $cloud */
        $cloud = $this->mockBuilder->getMock();

        /** @var Cloud $defaultCloud */
        $defaultCloud = $this->mockBuilder->getMock();

        $this->cloudManager->registerCloud('the-key', $cloud);
        $this->cloudManager->registerCloud('default-key', $defaultCloud);

        $this->assertSame($cloud, $this->cloudManager->getCloud('the-key'));
        $this->assertSame($defaultCloud, $this->cloudManager->getCloud('default-key'));
        $this->assertSame($defaultCloud, $this->cloudManager->getDefaultCloud());
    }

    public function testGetNonExistingDefaultCloud()
    {
        $this->mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Cloud');
        $this->mockBuilder->disableOriginalConstructor();

        /** @var Cloud $cloud */
        $cloud = $this->mockBuilder->getMock();

        $this->setExpectedException('InvalidArgumentException');
        $this->cloudManager->registerCloud('the-key', $cloud);
        $this->cloudManager->getDefaultCloud();
    }

    public function testGetNonExistingDefaultCloudWithEmptyCloudManager()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->cloudManager->getDefaultCloud();
    }

    public function testGetNonExistingCloud()
    {
        /** @var Cloud $cloud */
        $cloud = $this->mockBuilder->getMock();

        $this->setExpectedException('InvalidArgumentException');
        $this->cloudManager->registerCloud('the-key', $cloud);
        $this->cloudManager->getCloud('another-key');
    }

    public function testGetClouds()
    {
        /** @var Cloud $cloud1 */
        $cloud1 = $this->mockBuilder->getMock();

        /** @var Cloud $cloud2 */
        $cloud2 = $this->mockBuilder->getMock();

        $this->cloudManager->registerCloud('cloud1', $cloud1);
        $this->cloudManager->registerCloud('cloud2', $cloud2);

        $clouds = $this->cloudManager->getClouds();

        $this->assertEquals(2, count($clouds));
        $this->assertTrue(in_array('cloud1', array_keys($clouds)));
        $this->assertTrue(in_array('cloud2', array_keys($clouds)));
        $this->assertTrue(in_array($cloud1, $clouds));
        $this->assertTrue(in_array($cloud2, $clouds));
    }
}
