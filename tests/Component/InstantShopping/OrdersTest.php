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
 * File containing tests for the Order class.
 */

namespace Klarna\Rest\Tests\Component\InstantShopping;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\InstantShopping\Orders;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the Instant Shopping order resource.
 */
class OrdersTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testRetrive()
    {
        $json = <<<JSON
{
    "order_id": "f3392f8b-6116-4073-ab96-e330819e2c07",
    "order_amount": 50000,
    "order_tax_amount": 5000
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Orders($this->connector, 'auth-token-123456');
        $this->assertEquals('auth-token-123456', $order->getId());

        $order->retrieve();

        $this->assertEquals('f3392f8b-6116-4073-ab96-e330819e2c07', $order['order_id']);
        $this->assertEquals(50000, $order['order_amount']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/instantshopping/v1/authorizations/auth-token-123456', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when declining an order.
     *
     * @return void
     */
    public function testDeclines()
    {
        $this->mock->append(new Response(204));

        $order = new Orders($this->connector, 'auth-token-123456');
        $order->decline(['data' => 'sent in']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals(
            '/instantshopping/v1/authorizations/auth-token-123456',
            $request->getUri()->getPath()
        );
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));
        

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testApprove()
    {
        $json = <<<JSON
{
    "order_id": "45aa52f387871e3a210645d4",
    "fraud_status": "REJECTED"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Orders($this->connector, 'auth-token-123456');
        $data = $order->approve([
            'data' => 'sent in'
        ]);

        $this->assertEquals('45aa52f387871e3a210645d4', $data['order_id']);
        $this->assertEquals('REJECTED', $data['fraud_status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/instantshopping/v1/authorizations/auth-token-123456/orders', $request->getUri()->getPath());
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }
}
