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

use Xabbuh\PandaClient\Transformer\VideoTransformer;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class VideoTransformerTest extends TransformerTest
{
    protected function setUp()
    {
        $this->transformer = new VideoTransformer();

        parent::setUp();
    }

    public function testFromJSON()
    {
        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('foo', 'Video')
            ->will($this->returnValue('bar'));

        $this->assertEquals('bar', $this->transformer->stringToVideo('foo'));
    }

    public function testFromJSONCollection()
    {
        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('foo', 'Video')
            ->will($this->returnValue('bar'));

        $this->assertEquals('bar', $this->transformer->stringToVideoCollection('foo'));
    }
}
