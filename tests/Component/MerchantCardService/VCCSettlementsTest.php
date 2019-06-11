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
 * File containing tests for the Virtual Credit Card Settlements class.
 */

namespace Klarna\Rest\Tests\Component\MerchantCardService;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\MerchantCardService\VCCSettlements;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the Virtual Credit Card Settlements resource.
 */
class VCCSettlementsTest extends ResourceTestCase
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
    "settlement_id": "b0ec0bbd-534c-4b1c-b28a-628bf33c3324",
    "promise_id": "ee4a8e3a-9dfd-49e0-9ac8-ea2b6c76408c",
    "order_id": "f3392f8b-6116-4073-ab96-e330819e2c07"
}
JSON;
        $this->mock->append(
            new Response(201, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $vccSettlements = new VCCSettlements($this->connector);
        $data = $vccSettlements->create([
            'order_id' => '12345',
            'key_id' => 'asdfg-12345'
        ]);

        $this->assertEquals('f3392f8b-6116-4073-ab96-e330819e2c07', $data['order_id']);
        $this->assertEquals('b0ec0bbd-534c-4b1c-b28a-628bf33c3324', $data['settlement_id']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/merchantcard/v3/settlements', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"order_id":"12345","key_id":"asdfg-12345"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testFetch()
    {
        $this->setExpectedException('Klarna\Exceptions\NotApplicableException');

        $vccSettlements = new VCCSettlements($this->connector);
        $vccSettlements->fetch();
    }

    /**
     * Make sure that the request sent is correct
     *
     * @return void
     */
    public function testRetrieveSettlement()
    {
        $json =<<<JSON
{
    "settlement_id": "b0ec0bbd-534c-4b1c-b28a-628bf33c3324",
    "promise_id": "ee4a8e3a-9dfd-49e0-9ac8-ea2b6c76408c",
    "order_id": "f3392f8b-6116-4073-ab96-e330819e2c07"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $vccSettlements = new VCCSettlements($this->connector);
        $data = $vccSettlements->retrieveSettlement('settlement-id-123', 'secret-key');

        $this->assertEquals('f3392f8b-6116-4073-ab96-e330819e2c07', $data['order_id']);
        $this->assertEquals('b0ec0bbd-534c-4b1c-b28a-628bf33c3324', $data['settlement_id']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertTrue($request->hasHeader('KeyId'));
        $this->assertEquals('secret-key', $request->getHeader('KeyId')[0]);
        $this->assertEquals('/merchantcard/v3/settlements/settlement-id-123', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct
     *
     * @return void
     */
    public function testRetrieveOrderSettlement()
    {
        $json =<<<JSON
{
    "settlement_id": "b0ec0bbd-534c-4b1c-b28a-628bf33c3324",
    "promise_id": "ee4a8e3a-9dfd-49e0-9ac8-ea2b6c76408c",
    "order_id": "f3392f8b-6116-4073-ab96-e330819e2c07"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $vccSettlements = new VCCSettlements($this->connector);
        $data = $vccSettlements->retrieveOrderSettlement('order-id-123', 'secret-key');

        $this->assertEquals('f3392f8b-6116-4073-ab96-e330819e2c07', $data['order_id']);
        $this->assertEquals('b0ec0bbd-534c-4b1c-b28a-628bf33c3324', $data['settlement_id']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertTrue($request->hasHeader('KeyId'));
        $this->assertEquals('secret-key', $request->getHeader('KeyId')[0]);
        $this->assertEquals('/merchantcard/v3/settlements/order/order-id-123', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }
}
