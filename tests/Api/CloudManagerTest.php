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

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Api\CloudManager;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudManagerTest extends TestCase
{
    /**
     * @var \Xabbuh\PandaClient\Api\Cloud
     */
    private $cloud1;

    /**
     * @var \Xabbuh\PandaClient\Api\Cloud
     */
    private $cloud2;

    /**
     * @var CloudManager
     */
    private $cloudManager;

    protected function setUp(): void
    {
        $mockBuilder = $this->getMockBuilder('Xabbuh\PandaClient\Api\Cloud');
        $mockBuilder->disableOriginalConstructor();

        $this->cloud1 = $mockBuilder->getMock();
        $this->cloud2 = $mockBuilder->getMock();
        $this->cloudManager = new CloudManager();
        $this->cloudManager->setDefaultCloud('default-key');
    }

    public function testRegisterCloud()
    {
        $this->cloudManager->registerCloud('the-key', $this->cloud1);
        $this->cloudManager->registerCloud('default-key', $this->cloud2);

        $this->assertSame($this->cloud1, $this->cloudManager->getCloud('the-key'));
        $this->assertSame($this->cloud2, $this->cloudManager->getCloud('default-key'));
        $this->assertSame($this->cloud2, $this->cloudManager->getDefaultCloud());
    }

    public function testGetNonExistingDefaultCloud()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cloudManager->registerCloud('the-key', $this->cloud1);
        $this->cloudManager->getDefaultCloud();
    }

    public function testGetNonExistingDefaultCloudWithEmptyCloudManager()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cloudManager->getDefaultCloud();
    }

    public function testGetNonExistingCloud()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->cloudManager->registerCloud('the-key', $this->cloud1);
        $this->cloudManager->getCloud('another-key');
    }

    public function testGetClouds()
    {
        $this->cloudManager->registerCloud('cloud1', $this->cloud1);
        $this->cloudManager->registerCloud('cloud2', $this->cloud2);
        $clouds = $this->cloudManager->getClouds();

        $this->assertEquals(2, count($clouds));
        $this->assertTrue(in_array('cloud1', array_keys($clouds)));
        $this->assertTrue(in_array('cloud2', array_keys($clouds)));
        $this->assertTrue(in_array($this->cloud1, $clouds));
        $this->assertTrue(in_array($this->cloud2, $clouds));
    }
}
