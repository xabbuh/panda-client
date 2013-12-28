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

use Xabbuh\PandaClient\Transformer\TransformerFactory;

/**
 * Test the TransformerFactory class.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TransformerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that appropriate instances are returned for registered
     * transformer class names.
     */
    public function testGetTransformer()
    {
        $transformerFactory = new TransformerFactory();

        // first, register transformer class names
        $transformerFactory->registerTransformer(
            'Cloud',
            'Xabbuh\PandaClient\Transformer\CloudTransformer'
        );
        $transformerFactory->registerTransformer(
            'Encoding',
            'Xabbuh\PandaClient\Transformer\EncodingTransformer'
        );
        $transformerFactory->registerTransformer(
            'Notifications',
            'Xabbuh\PandaClient\Transformer\NotificationsTransformer'
        );
        $transformerFactory->registerTransformer(
            'Profile',
            'Xabbuh\PandaClient\Transformer\ProfileTransformer'
        );
        $transformerFactory->registerTransformer(
            'Video',
            'Xabbuh\PandaClient\Transformer\VideoTransformer'
        );

        // then check that appropriate instances are returned by the factory
        $this->assertTrue(is_object($transformerFactory->get('Cloud')));
        $this->assertEquals(
            'Xabbuh\PandaClient\Transformer\CloudTransformer',
            get_class($transformerFactory->get('Cloud'))
        );
        $this->assertTrue(is_object($transformerFactory->get('Encoding')));
        $this->assertEquals(
            'Xabbuh\PandaClient\Transformer\EncodingTransformer',
            get_class($transformerFactory->get('Encoding'))
        );
        $this->assertTrue(is_object($transformerFactory->get('Notifications')));
        $this->assertEquals(
            'Xabbuh\PandaClient\Transformer\NotificationsTransformer',
            get_class($transformerFactory->get('Notifications'))
        );
        $this->assertTrue(is_object($transformerFactory->get('Profile')));
        $this->assertEquals(
            'Xabbuh\PandaClient\Transformer\ProfileTransformer',
            get_class($transformerFactory->get('Profile'))
        );
        $this->assertTrue(is_object($transformerFactory->get('Video')));
        $this->assertEquals(
            'Xabbuh\PandaClient\Transformer\VideoTransformer',
            get_class($transformerFactory->get('Video'))
        );
    }

    /**
     * Test that an exception is thrown if a transformer is requested for
     * a model without a registered transformer.
     */
    public function testInvalidArgumentExceptionWhenRetrieving()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $transformerFactory = new TransformerFactory();
        $transformerFactory->get('Cloud');
    }
}
