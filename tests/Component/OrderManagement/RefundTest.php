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
 * File containing tests for the Refund class.
 */

namespace Klarna\Rest\Tests\Component\OrderManagement;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\OrderManagement\Refund;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the refund resource.
 */
class RefundTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testFetch()
    {
        $json = <<<JSON
{
    "refund_id": "123456",
    "order_id": "order-id-7"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $refund = new Refund($this->connector, '/path', '123456');
        $refund['order_id'] = '0';

        $refund->fetch();

        $this->assertEquals('order-id-7', $refund['order_id']);
        $this->assertEquals('123456', $refund->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/path/refunds/123456', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated
     * when creating the new refund.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->mock->append(
            new Response(201, ['Location' => '/path/to/order/order-id-7/refunds/new-refund-id'])
        );

        $refund = new Refund($this->connector, '/path/to/order/order-id-7');
        $location = $refund->create(['refunded_amount' => '100'])
            ->getLocation();

        $this->assertEquals('/path/to/order/order-id-7/refunds/new-refund-id', $location);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/path/to/order/order-id-7/refunds', $request->getUri()->getPath());
        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"refunded_amount":"100"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }
}
