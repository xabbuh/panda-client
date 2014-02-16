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

use Xabbuh\PandaClient\Transformer\ProfileTransformer;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ProfileTransformerTest extends TransformerTest
{
    protected function setUp()
    {
        $this->transformer = new ProfileTransformer();

        parent::setUp();
    }

    public function testFromJSON()
    {
        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('foo', 'Profile')
            ->will($this->returnValue('bar'));

        $this->assertEquals('bar', $this->transformer->stringToProfile('foo'));
    }

    public function testFromJSONCollection()
    {
        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('foo', 'Profile')
            ->will($this->returnValue('bar'));

        $this->assertEquals('bar', $this->transformer->stringToProfileCollection('foo'));
    }
}
