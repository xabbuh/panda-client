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

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Transformer\CloudTransformerInterface;
use Xabbuh\PandaClient\Transformer\EncodingTransformerInterface;
use Xabbuh\PandaClient\Transformer\NotificationsTransformerInterface;
use Xabbuh\PandaClient\Transformer\ProfileTransformerInterface;
use Xabbuh\PandaClient\Transformer\TransformerRegistry;
use Xabbuh\PandaClient\Transformer\VideoTransformerInterface;

/**
 * Tests the TransformerRegistry class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TransformerRegistryTest extends TestCase
{
    /**
     * @var TransformerRegistry
     */
    private $transformers;

    /**
     * @var CloudTransformerInterface
     */
    private $cloudTransformer;

    /**
     * @var EncodingTransformerInterface
     */
    private $encodingTransformer;

    /**
     * @var NotificationsTransformerInterface
     */
    private $notificationsTransformer;

    /**
     * @var ProfileTransformerInterface
     */
    private $profileTransformer;

    /**
     * @var VideoTransformerInterface
     */
    private $videoTransformer;

    protected function setUp()
    {
        $this->transformers = new TransformerRegistry();
        $this->cloudTransformer = $this->getMockBuilder('Xabbuh\PandaClient\Transformer\CloudTransformerInterface')->getMock();
        $this->encodingTransformer = $this->getMockBuilder('Xabbuh\PandaClient\Transformer\EncodingTransformerInterface')->getMock();
        $this->notificationsTransformer = $this->getMockBuilder('Xabbuh\PandaClient\Transformer\NotificationsTransformerInterface')->getMock();
        $this->profileTransformer = $this->getMockBuilder('Xabbuh\PandaClient\Transformer\ProfileTransformerInterface')->getMock();
        $this->videoTransformer = $this->getMockBuilder('Xabbuh\PandaClient\Transformer\VideoTransformerInterface')->getMock();
    }

    public function testGetTransformer()
    {
        $this->transformers->setCloudTransformer($this->cloudTransformer);
        $this->transformers->setEncodingTransformer($this->encodingTransformer);
        $this->transformers->setNotificationsTransformer($this->notificationsTransformer);
        $this->transformers->setProfileTransformer($this->profileTransformer);
        $this->transformers->setVideoTransformer($this->videoTransformer);

        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\CloudTransformerInterface',
            $this->transformers->getCloudTransformer()
        );
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\EncodingTransformerInterface',
            $this->transformers->getEncodingTransformer()
        );
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\NotificationsTransformerInterface',
            $this->transformers->getNotificationsTransformer()
        );
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\ProfileTransformerInterface',
            $this->transformers->getProfileTransformer()
        );
        $this->assertInstanceOf(
            'Xabbuh\PandaClient\Transformer\VideoTransformerInterface',
            $this->transformers->getVideoTransformer()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithoutCloudTransformer()
    {
        $this->transformers->getCloudTransformer();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithoutEncodingTransformer()
    {
        $this->transformers->getEncodingTransformer();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithoutNotificationsTransformer()
    {
        $this->transformers->getNotificationsTransformer();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithoutProfileTransformer()
    {
        $this->transformers->getProfileTransformer();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentExceptionWithoutVideoTransformer()
    {
        $this->transformers->getVideoTransformer();
    }
}
