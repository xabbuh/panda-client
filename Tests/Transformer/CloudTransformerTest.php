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

use Xabbuh\PandaClient\Transformer\CloudTransformer;

/**
 * Tests for the cloud transformer.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class CloudTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the CloudTransformer::fromJson() method.
     */
    public function testFromJSON()
    {
        $jsonString = '{
          "id": "e122090f4e506ae9ee266c3eb78a8b67",
          "name": "my_first_cloud",
          "s3_videos_bucket": "my-example-bucket",
          "s3_private_access":false,
          "url": "http://my-example-bucket.s3.amazonaws.com/",
          "created_at": "2010/03/18 12:56:04 +0000",
          "updated_at": "2010/03/18 12:59:06 +0000"
        }';
        $transformer = new CloudTransformer();
        $cloud = $transformer->fromJSON($jsonString);
        $this->assertEquals('e122090f4e506ae9ee266c3eb78a8b67', $cloud->getId());
        $this->assertEquals('my_first_cloud', $cloud->getName());
        $this->assertEquals('my-example-bucket', $cloud->getS3VideosBucket());
        $this->assertEquals(false, $cloud->isS3AccessPrivate());
        $this->assertEquals('http://my-example-bucket.s3.amazonaws.com/', $cloud->getUrl());
        $this->assertEquals('2010/03/18 12:56:04 +0000', $cloud->getCreatedAt());
        $this->assertEquals('2010/03/18 12:59:06 +0000', $cloud->getUpdatedAt());
    }
}
