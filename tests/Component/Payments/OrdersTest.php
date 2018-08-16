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
 * File containing tests for the Orders class.
 */

namespace Klarna\Rest\Tests\Component\Payments;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Payments\Orders;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the Payments session resource.
 */
class OrdersTest extends ResourceTestCase
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
    "order_id": "asdf-1234",
    "redirect_url": "https://credit.klarna.com/v1/sessions/0b1d9815-165e-42e2-8867-35bc03789e00/redirect",
    "fraud_status": "ACCEPTED"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $order = new Orders($this->connector, 'my-auth-token');
        $data = $order->create([
            'order_amount' => 10000,
            'purchase_currency' => 'eur',
        ]);

        $this->assertEquals('asdf-1234', $data['order_id']);
        $this->assertEquals('ACCEPTED', $data['fraud_status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/payments/v1/authorizations/my-auth-token/order', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"order_amount":10000,"purchase_currency":"eur"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated.
     *
     * @return void
     */
    public function testGenerateCutomerToken()
    {
        $json =<<<JSON
{
    "token_id": "0b1d9815-165e-42e2-8867-35bc03789e00",
    "redirect_url": "https://credit.klarna.com/v1/sessions/0b1d9815-165e-42e2-8867-35bc03789e00/redirect"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $order = new Orders($this->connector, 'my-auth-token');
        $data = $order->generateToken([
            'intended_use' => 'SUBSCRIPTION',
            'purchase_country' => 'US',
        ]);

        $this->assertEquals('0b1d9815-165e-42e2-8867-35bc03789e00', $data['token_id']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/payments/v1/authorizations/my-auth-token/customer-token', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"intended_use":"SUBSCRIPTION","purchase_country":"US"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated.
     *
     * @return void
     */
    public function testCancelAuthorization()
    {
        $this->mock->append(
            new Response(204, [
                'Content-Type' => 'application/json'
            ])
        );

        $order = new Orders($this->connector, 'my-auth-token');
        $order->cancelAuthorization();

        $request = $this->mock->getLastRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/payments/v1/authorizations/my-auth-token', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('', strval($request->getBody()));

        $this->assertAuthorization($request);
    }
}
