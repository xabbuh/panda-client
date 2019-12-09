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

use Http\Discovery\MessageFactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Api\HttplugClient;
use Xabbuh\PandaClient\Exception\ApiException;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 * @author Christophe Coevoet <stof@notk.org>
 */
class HttplugClientTest extends TestCase
{
    /**
     * @var HttplugClient
     */
    private $httpClient;

    /**
     * @var Client
     */
    private $mockClient;

    protected function setUp(): void
    {
        $this->mockClient = new Client();
        $this->httpClient = new HttplugClient($this->mockClient);
        $this->httpClient->setAccount(new Account(
            md5(uniqid()),
            md5(uniqid()),
            'api.pandastream.com'
        ));
        $this->httpClient->setCloudId(md5(uniqid()));
    }

    public function testGet()
    {
        $this->configureMockResponse(200, 'test');

        $response = $this->httpClient->get('/foo');

        $this->assertSame('test', $response);
        $this->assertRequestMethod('GET');
    }

    public function testDelete()
    {
        $this->configureMockResponse(200, 'deleted');

        $response = $this->httpClient->delete('/foo');

        $this->assertSame('deleted', $response);
        $this->assertRequestMethod('DELETE');
    }

    public function testPost()
    {
        $this->configureMockResponse(200, 'test');

        $response = $this->httpClient->post('/foo');

        $this->assertSame('test', $response);
        $this->assertRequestMethod('POST');
    }

    public function testPut()
    {
        $this->configureMockResponse(200, 'test');

        $response = $this->httpClient->put('/foo');

        $this->assertSame('test', $response);
        $this->assertRequestMethod('PUT');
    }

    public function testApiException()
    {
        $this->expectException(ApiException::class);
        $this->expectExceptionCode(208);

        $error = new \stdClass();
        $error->error = 500;
        $error->message = 'error message';
        $this->configureMockResponse(208, json_encode($error));

        $this->httpClient->get('/foo');
    }

    private function configureMockResponse($statusCode = 200, $responseBody = '')
    {
        $response = MessageFactoryDiscovery::find()->createResponse($statusCode, null, array(), $responseBody);

        $this->mockClient->addResponse($response);
    }

    private function assertRequestMethod($expectedMethod)
    {
        /** @var RequestInterface[] $requests */
        $requests = $this->mockClient->getRequests();

        $this->assertCount(1, $requests, '1 request has been sent');
        $this->assertSame($expectedMethod, $requests[0]->getMethod());
    }
}
