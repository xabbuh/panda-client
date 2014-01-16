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

use Xabbuh\PandaClient\Model\Cloud;

/**
 * Test the Cloud model class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getter and setter methods.
     */
    public function testGetterSetter()
    {
        $cloud = new Cloud();
        $cloud->setId("e122090f4e506ae9ee266c3eb78a8b67");
        $cloud->setName("my_first_cloud");
        $cloud->setS3VideosBucket("my-example-bucket");
        $cloud->setS3PrivateAccess(false);
        $cloud->setUrl("http://my-example-bucket.s3.amazonaws.com/");
        $cloud->setCreatedAt("2010/03/18 12:56:04 +0000");
        $cloud->setUpdatedAt("2010/03/18 12:59:06 +0000");

        $this->assertEquals("e122090f4e506ae9ee266c3eb78a8b67", $cloud->getId());
        $this->assertEquals("my_first_cloud", $cloud->getName());
        $this->assertEquals("my-example-bucket", $cloud->getS3VideosBucket());
        $this->assertFalse($cloud->isS3AccessPrivate());
        $this->assertEquals("http://my-example-bucket.s3.amazonaws.com/", $cloud->getUrl());
        $this->assertEquals("2010/03/18 12:56:04 +0000", $cloud->getCreatedAt());
        $this->assertEquals("2010/03/18 12:59:06 +0000", $cloud->getUpdatedAt());
    }
}
