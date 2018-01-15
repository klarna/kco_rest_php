<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing tests for the Connector class.
 */

namespace Klarna\Rest\Tests\Component\Transport;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Tests\Component\TestCase;
use Klarna\Rest\Transport\UserAgent;
use Psr\Http\Message\RequestInterface;

/**
 * Component test cases for the connector class.
 */
class ConnectorTest extends TestCase
{
    /**
     * Make sure the request is created properly.
     *
     * @return void
     */
    public function testCreateRequest()
    {
        $request = $this->connector->createRequest(
            'https://localhost:8888/path-here?q=1',
            'POST'
        );

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            new Uri('https://localhost:8888/path-here?q=1'),
            $request->getUri()
        );

        $this->assertEquals(
            strval($this->connector->getUserAgent()),
            $request->getHeader('User-Agent')[0]
        );
    }

    /**
     * Make sure that the request sent returns an response.
     *
     * @return void
     */
    public function testSend()
    {
        $response = new Response(200);
        $this->mock->append($response);

        $request = $this->connector->createRequest('http://somewhere/path', 'POST');
        $this->assertSame($response, $this->connector->send($request));
    }

    /**
     * Make sure that an API error response throws a connector exception.
     *
     * @return void
     */
    public function testSendError()
    {
        $json = <<<JSON
{
    "error_code": "ERR_1",
    "error_messages": [
        "msg1",
        "msg2"
    ],
    "correlation_id": "cid_1"
}
JSON;
        $response = new Response(
            500,
            ['Content-Type' => 'application/json'],
            $json
        );
        $this->mock->append($response);

        $this->setExpectedException(
            'Klarna\Rest\Transport\Exception\ConnectorException',
            'ERR_1: msg1, msg2 (#cid_1)'
        );

        $request = $this->connector->createRequest('http://somewhere/path', 'POST');
        $this->connector->send($request);
    }

    /**
     * Make sure that an error response throws an exception.
     *
     * @return void
     */
    public function testSendGuzzleError()
    {
        $response = new Response(404);
        $this->mock->append($response);

        $this->setExpectedException('GuzzleHttp\Exception\ClientException');

        $request = $this->connector->createRequest('http://somewhere/path', 'POST');
        $this->connector->send($request);
    }

    /**
     * Make sure that the factory method creates a connector as expected.
     *
     * @return void
     */
    public function testCreate()
    {
        $userAgent = $this->getMockBuilder(UserAgent::class)
            ->getMock();

        $connector = Connector::create(
            self::MERCHANT_ID,
            self::SHARED_SECRET,
            self::BASE_URL,
            $userAgent
        );

        $this->assertSame($userAgent, $connector->getUserAgent());
        $this->assertEquals(self::BASE_URL, $connector->getClient()->getConfig('base_uri'));
    }
}
