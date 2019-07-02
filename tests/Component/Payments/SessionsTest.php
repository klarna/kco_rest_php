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

namespace Klarna\Rest\Tests\Component\Payments;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\Payments\Sessions;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the Payments session resource.
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
    "session_id": "0b1d9815-165e-42e2-8867-35bc03789e00",
    "client_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $session = new Sessions($this->connector);
        $session->create(['data' => 'goes here']);

        $this->assertEquals('/payments/v1/sessions/0b1d9815-165e-42e2-8867-35bc03789e00', $session->getLocation());
        $this->assertEquals('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9', $session['client_token']);
        $this->assertEquals('0b1d9815-165e-42e2-8867-35bc03789e00', $session->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/payments/v1/sessions', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->mock->append(
            new Response(
                204,
                ['Content-Type' => 'application/json']
            )
        );

        $session = new Sessions($this->connector, '0b1d9815');
        $session->update(['data' => 'sent in']);

        $this->assertEquals('0b1d9815', $session->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/payments/v1/sessions/0b1d9815', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testFetch()
    {
        $json = <<<JSON
{
    "client_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9",
    "status": "completed",
    "order_amount": 123
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $session = new Sessions($this->connector, '0b1d9815');
        $session['status'] = 'incompleted';

        $this->assertEquals('0b1d9815', $session->getId());
        $session->fetch();
        $this->assertEquals('0b1d9815', $session->getId()); // Check if the ID field still exists

        $this->assertEquals('completed', $session['status']);
        $this->assertEquals(123, $session['order_amount']);
        $this->assertEquals('/payments/v1/sessions/0b1d9815', $session->getLocation());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertEquals('/payments/v1/sessions/0b1d9815', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }
}
