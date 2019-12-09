<?php

/*
 * This file is part of the XabbuhPandaClient package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\PandaClient\Tests\Serializer\Symfony;

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Serializer\Symfony\Normalizer;

class NormalizerTest extends TestCase
{
    /**
     * @var Normalizer;
     */
    private $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new Normalizer();
    }

    public function testWithVideo()
    {
        $data = array('id' => 'd891d9a45c698d587831466f236c6c6c');
        $object = $this->normalizer->denormalize($data, 'Xabbuh\PandaClient\Model\Video');

        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Video', $object);
    }

    public function testWithVideoCollection()
    {
        $data = array(
            array('id' => 'd891d9a45c698d587831466f236c6c6c'),
            array('id' => 'd891d9a45c698d587831466f236c6c6c'),
        );
        $collection = $this->normalizer->denormalize($data, 'Xabbuh\PandaClient\Model\Video');

        $this->assertTrue(is_array($collection));
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Video', $collection[0]);
        $this->assertInstanceOf('Xabbuh\PandaClient\Model\Video', $collection[1]);
    }

    public function testWithEmptyVideoCollection()
    {
        $data = array();
        $collection = $this->normalizer->denormalize($data, 'Xabbuh\PandaClient\Model\Video');

        $this->assertTrue(is_array($collection));
        $this->assertEquals(0, count($collection));
    }
}
