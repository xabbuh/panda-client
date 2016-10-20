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

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Xabbuh\PandaClient\Transformer\BaseTransformer;
     */
    protected $transformer;

    /**
     * @var \Xabbuh\PandaClient\Serializer\SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $serializer;

    protected function setUp()
    {
        $this->createSerializer();
        $this->transformer->setSerializer($this->serializer);
    }

    private function createSerializer()
    {
        $this->serializer = $this->getMockBuilder('\Xabbuh\PandaClient\Serializer\SerializerInterface')->getMock();
    }
}
