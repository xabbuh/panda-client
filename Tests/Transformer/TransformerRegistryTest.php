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
class TransformerRegistryTest extends \PHPUnit_Framework_TestCase
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
        $this->cloudTransformer = $this->getMock(
            'Xabbuh\PandaClient\Transformer\CloudTransformerInterface'
        );
        $this->encodingTransformer = $this->getMock(
            'Xabbuh\PandaClient\Transformer\EncodingTransformerInterface'
        );
        $this->notificationsTransformer = $this->getMock(
            'Xabbuh\PandaClient\Transformer\NotificationsTransformerInterface'
        );
        $this->profileTransformer = $this->getMock(
            'Xabbuh\PandaClient\Transformer\ProfileTransformerInterface'
        );
        $this->videoTransformer = $this->getMock(
            'Xabbuh\PandaClient\Transformer\VideoTransformerInterface'
        );
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

    public function testInvalidArgumentExceptionWithoutCloudTransformer()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->transformers->getCloudTransformer();
    }

    public function testInvalidArgumentExceptionWithoutEncodingTransformer()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->transformers->getEncodingTransformer();
    }

    public function testInvalidArgumentExceptionWithoutNotificationsTransformer()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->transformers->getNotificationsTransformer();
    }

    public function testInvalidArgumentExceptionWithoutProfileTransformer()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->transformers->getProfileTransformer();
    }

    public function testInvalidArgumentExceptionWithoutVideoTransformer()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->transformers->getVideoTransformer();
    }
}
