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
 * File containing tests for the Sessions class.
 */

namespace Klarna\Rest\Tests\Component\HostedPaymentPage;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\HostedPaymentPage\Sessions;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the HPP session resource.
 */
class SessionsTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the location is updated.
     *
     * @return void
     */
    public function testCreate()
    {
        $json =<<<JSON
{
    "distribution_url": "https://api.klarna.com/hpp/v1/sessions/9cbc9884-1fdb-45a8-9694-9340340d0436/distribution",
    "redirect_url": "https://buy.klarna.com/hpp/9cbc9884-1fdb-45a8-9694-9340340d0436"
}
JSON;
        $this->mock->append(
            new Response(201, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $session = new Sessions($this->connector);
        $data = $session->create(['data' => 'goes here']);

        $this->assertEquals('https://buy.klarna.com/hpp/9cbc9884-1fdb-45a8-9694-9340340d0436', $data['redirect_url']);
        $this->assertEquals(null, $session->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/hpp/v1/sessions', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct
     *
     * @return void
     */
    public function testDistributeLink()
    {
        $this->mock->append(
            new Response(
                200
            )
        );

        $session = new Sessions($this->connector, 'session-id-123');
        $session->distributeLink(['data' => 'sent in']);

        $this->assertEquals('session-id-123', $session->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/hpp/v1/sessions/session-id-123/distribution', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct.
     *
     * @return void
     */
    public function testFetch()
    {
        $json =<<<JSON
{
    "authorization_token": "b4bd3423-24e3",
    "status": "COMPLETED",
    "updated_at": "2038-01-19T03:14:07.000Z"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $session = new Sessions($this->connector, 'session-id-123');
        $data = $session->fetch();

        $this->assertEquals('b4bd3423-24e3', $data['authorization_token']);
        $this->assertEquals('COMPLETED', $data['status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertEquals('/hpp/v1/sessions/session-id-123', $request->getUri()->getPath());
        $this->assertEquals('', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct
     *
     * @return void
     */
    public function testDisableSession()
    {
        $this->mock->append(
            new Response(
                204
            )
        );

        $session = new Sessions($this->connector, 'session-id-123');
        $session->disable();

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::DELETE, $request->getMethod());
        $this->assertEquals('/hpp/v1/sessions/session-id-123', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct
     *
     * @return void
     */
    public function testDisableUndefinedSession()
    {
        $this->setExpectedException(
            'RuntimeException',
            'HPP Session ID is not defined'
        );

        $session = new Sessions($this->connector);
        $session->disable();
    }
}
