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
 * File containing tests for the Tokens class.
 */

namespace Klarna\Rest\Tests\Component\CustomerToken;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\CustomerToken\Tokens;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the token resource.
 */
class TokensTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testReadTokenDetails()
    {
        $json = <<<JSON
{
    "payment_method_type": "INVOICE",
    "status": "ACTIVE"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $token = new Tokens($this->connector, 'my-token-id');
        $token->fetch();

        $this->assertEquals(
            '/customer-token/v1/tokens/my-token-id',
            $token->getLocation()
        );
        $this->assertEquals('INVOICE', $token['payment_method_type']);
        $this->assertEquals('ACTIVE', $token['status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertEquals('/customer-token/v1/tokens/my-token-id', $request->getUri()->getPath());
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated.
     *
     * @return void
     */
    public function testTokenCreateOrder()
    {
        $json =<<<JSON
{
    "fraud_status": "ACCEPTED",
    "order_id": "order-id-123",
    "redirect_url": "https://example.com/orders"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $token = new Tokens($this->connector, 'my-token-id');
        $order = $token->createOrder([
            'amount' => '123',
            'purchase_currency' => 'eur'
        ]);

        $this->assertEquals('ACCEPTED', $order['fraud_status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/customer-token/v1/tokens/my-token-id/order', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('{"amount":"123","purchase_currency":"eur"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request contains Klarna-Idempotency-Key.
     *
     * @return void
     */
    public function testTokenCreateOrderWithKey()
    {
        $json =<<<JSON
{
    "fraud_status": "ACCEPTED",
    "order_id": "order-id-123",
    "redirect_url": "https://example.com/orders"
}
JSON;
        $this->mock->append(
            new Response(200, [
                'Content-Type' => 'application/json'
            ], $json)
        );

        $token = new Tokens($this->connector, 'my-token-id');
        $order = $token->createOrder([
            'amount' => '123',
            'purchase_currency' => 'eur'
        ], 'my-idempotency-key');

        $this->assertEquals('ACCEPTED', $order['fraud_status']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals('/customer-token/v1/tokens/my-token-id/order', $request->getUri()->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type')[0]);
        $this->assertEquals('my-idempotency-key', $request->getHeader('Klarna-Idempotency-Key')[0]);
        $this->assertEquals('{"amount":"123","purchase_currency":"eur"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testUpdateStatus()
    {
        $this->mock->append(
            new Response(
                202,
                ['Content-Type' => 'application/json']
            )
        );

        $token = new Tokens($this->connector, 'my-token-id');
        $token->updateTokenStatus([
            'status' => 'CANCELLED'
        ]);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::PATCH, $request->getMethod());
        $this->assertEquals('/customer-token/v1/tokens/my-token-id/status', $request->getUri()->getPath());
        $this->assertEquals('{"status":"CANCELLED"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }
}
