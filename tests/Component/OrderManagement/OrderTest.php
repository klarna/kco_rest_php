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

namespace Klarna\Rest\Tests\Component\OrderManagement;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\OrderManagement\Capture;
use Klarna\Rest\OrderManagement\Refund;
use Klarna\Rest\OrderManagement\Order;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the order resource.
 */
class OrderTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * the order.
     *
     * @return void
     */
    public function testFetch()
    {
        $json = <<<JSON
{
    "order_id": "0002",
    "updated": "from json",
    "captures": [
        {
            "capture_id": "1002",
            "test": "data"
        }
    ],
    "refunds": [
        {
            "refund_id": "ref-1",
            "data": 123
        }
    ]
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');
        $order['updated'] = 'not from json';

        $order->fetch();

        $this->assertEquals('from json', $order['updated']);
        $this->assertEquals('0002', $order->getId());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::GET, $request->getMethod());
        $this->assertEquals('/ordermanagement/v1/orders/0002', $request->getUri()->getPath());

        $this->assertAuthorization($request);

        $capture = $order['captures'][0];
        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertEquals($capture->getId(), $capture['capture_id']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals('data', $capture['test']);
    }

    /**
     * Make sure that the request sent is correct when acknowledging an order.
     *
     * @return void
     */
    public function testAcknowledge()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->acknowledge();

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/acknowledge',
            $request->getUri()->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when cancelling an order.
     *
     * @return void
     */
    public function testCancel()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->cancel();

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/cancel',
            $request->getUri()->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when extending authorization time.
     *
     * @return void
     */
    public function testExtendAuthorizationTime()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->extendAuthorizationTime();

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/extend-authorization-time',
            $request->getUri()->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when releasing remaining
     * authorization.
     *
     * @return void
     */
    public function testReleaseRemainingAuthorization()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->releaseRemainingAuthorization();

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/release-remaining-authorization',
            $request->getUri()->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating authorization.
     *
     * @return void
     */
    public function testUpdateAuthorization()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateAuthorization(['data' => 'sent in']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::PATCH, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/authorization',
            $request->getUri()->getPath()
        );

        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating merchant references.
     *
     * @return void
     */
    public function testUpdateMerchantReferences()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateMerchantReferences(['data' => 'sent in']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::PATCH, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/merchant-references',
            $request->getUri()->getPath()
        );

        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating customer details.
     *
     * @return void
     */
    public function testUpdateCustomerDetails()
    {
        $this->mock->append(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateCustomerDetails(['data' => 'sent in']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::PATCH, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/customer-details',
            $request->getUri()->getPath()
        );

        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when performing a refund.
     *
     * @return void
     */
    public function testRefund()
    {
        $this->mock->append(new Response(201, ['Location' => 'http://example.com']));

        $order = new Order($this->connector, '0002');
        $refund = $order->refund(['data' => 'sent in']);

        $this->assertInstanceOf(Refund::class, $refund);

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/refunds',
            $request->getUri()->getPath()
        );

        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when fetching a refund.
     *
     * @return void
     */
    public function testFetchRefund()
    {
        $json = <<<JSON
{
    "refund_id": "refund-id-123",
    "order_id": "order-id-567"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $refund = $order->fetchRefund('refund-id-123');
        $this->assertInstanceOf(Refund::class, $refund);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/refunds/refund-id-123',
            $refund->getLocation()
        );

        $this->assertEquals('order-id-567', $refund['order_id']);
        $this->assertEquals('refund-id-123', $refund->getId());
        $this->assertEquals($refund->getId(), $refund['refund_id']);
    }

    /**
     * Make sure that the request sent is correct when fetching an existing refund.
     *
     * @return void
     */
    public function testFetchExistingRefund()
    {
        $json = <<<JSON
{
    "refund_id": "refund-id-123",
    "order_id": "order-id-567"
}
JSON;
        $json2 = <<<JSON
{
    "refund_id": "refund-id-XXX",
    "order_id": "order-id-XXX"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $order->fetchRefund('refund-id-123');
        $order['refunds'][0]['test_data'] = 'abc';

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json2
            )
        );

        $order->fetchRefund('refund-id-XXX');

        $refund = $order->fetchRefund('refund-id-123');

        $this->assertEquals('abc', $refund['test_data']);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated
     * when creating an order.
     *
     * @return void
     */
    public function testCreateCapture()
    {
        $this->mock->append(
            new Response(201, ['Location' => 'http://somewhere/a-path'])
        );

        $order = new Order($this->connector, '0002');
        $capture = $order->createCapture(['data' => 'goes here']);

        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertEquals('http://somewhere/a-path', $capture->getLocation());

        $request = $this->mock->getLastRequest();
        $this->assertEquals(Method::POST, $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures',
            $request->getUri()->getPath()
        );

        $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture.
     *
     * @return void
     */
    public function testFetchCapture()
    {
        $json = <<<JSON
{
    "capture_id": "1002",
    "updated": "from json"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = $order->fetchCapture('1002');
        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1002',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture that exists in the captures list.
     *
     * @return void
     */
    public function testFetchCaptureExisting()
    {
        $json = <<<JSON
{
    "capture_id": "1002",
    "updated": "from json"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = new Capture($this->connector, $order->getLocation(), '1002');
        $capture['capture_id'] = '1002';
        $capture['updated'] = 'not from json';

        $order['captures'][] = $capture;

        $capture = $order->fetchCapture('1002');
        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1002',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture that is not already in the captures list.
     *
     * @return void
     */
    public function testFetchCaptureNew()
    {
        $json = <<<JSON
{
    "capture_id": "1003",
    "updated": "from json"
}
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = new Capture($this->connector, $order->getLocation(), '1002');
        $capture['capture_id'] = '1002';
        $capture['updated'] = 'not from json';

        $order['captures'][] = $capture;

        $capture = $order->fetchCapture('1003');
        $this->assertInstanceOf(Capture::class, $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1003',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1003', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching all captures.
     *
     * @return void
     */
    public function testFetchCaptures()
    {
        $json = <<<JSON
[
    {
        "capture_id": "1001",
        "updated": "from json 1"
    },
    {
        "capture_id": "1002",
        "updated": "from json 2"
    }
]
JSON;

        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $order = new Order($this->connector, '0002');

        $captures = $order->fetchCaptures();
        $this->assertCount(2, $captures, 'Mismatched amount of captures');

        $this->assertInstanceOf(Capture::class, $captures[0]);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1001',
            $captures[0]->getLocation()
        );

        $this->assertEquals('from json 1', $captures[0]['updated']);
        $this->assertEquals('1001', $captures[0]->getId());
        $this->assertEquals('from json 2', $captures[1]['updated']);
        $this->assertEquals('1002', $captures[1]->getId());
    }
}
