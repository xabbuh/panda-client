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

use PHPUnit\Framework\TestCase;
use Xabbuh\PandaClient\Api\Account;
use Xabbuh\PandaClient\Api\HttpClient;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * @group legacy
 */
class HttpClientTest extends TestCase
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    private $guzzleClient;

    protected function setUp()
    {
        $this->httpClient = new HttpClient();
        $this->httpClient->setAccount(new Account(
            md5(uniqid()),
            md5(uniqid()),
            'api.pandastream.com'
        ));
        $this->httpClient->setCloudId(md5(uniqid()));
    }

    public function testGet()
    {
        $this->createGuzzleMock('get');

        $this->httpClient->get('/foo');
    }

    public function testDelete()
    {
        $this->createGuzzleMock('delete');

        $this->httpClient->delete('/foo');
    }

    public function testPost()
    {
        $this->createGuzzleMock('post');

        $this->httpClient->post('/foo');
    }

    public function testPut()
    {
        $this->createGuzzleMock('put');

        $this->httpClient->put('/foo');
    }

    /**
     * @expectedException \Xabbuh\PandaClient\Exception\ApiException
     * @expectedExceptionCode 208
     */
    public function testApiException()
    {
        $error = new \stdClass();
        $error->error = 500;
        $error->message = 'error message';
        $this->createGuzzleMock('get', 208, json_encode($error));

        $this->httpClient->get('/foo');
    }

    private function createGuzzleMock($method, $statusCode = 200, $body = '')
    {
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($statusCode));
        $response->expects($this->any())
            ->method('getBody')
            ->with(true)
            ->will($this->returnValue($body));

        $request = $this->getMockBuilder('Guzzle\Http\Message\Request')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        $request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $this->guzzleClient = $this->getMockBuilder('\Guzzle\Http\Client')->getMock();
        $this->httpClient->setGuzzleClient($this->guzzleClient);
        $this->guzzleClient
            ->expects($this->once())
            ->method($method)
            ->will($this->returnValue($request));
    }
}
