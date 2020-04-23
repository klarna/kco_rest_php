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
 * File containing tests for the Payouts class.
 */

namespace Klarna\Rest\Tests\Component\Settlements;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Settlements\Payouts;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the token resource.
 */
class PayoutsTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testFetch()
    {
        $this->setExpectedException('Klarna\Exceptions\NotApplicableException');

        $reports = new Payouts($this->connector);
        $reports->fetch();
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetPayout()
    {
        $json = <<<JSON
{
    "totals": {
        "sale_amount": 500
    },
    "payment_reference": "XISA93DJ"
}
JSON;
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $payout = new Payouts($this->connector);
        $data = $payout->getPayout('XISA93DJ');

        $this->assertEquals(500, $data['totals']['sale_amount']);
        $this->assertEquals('XISA93DJ', $data['payment_reference']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/payouts/XISA93DJ', $request->getUri()->getPath());
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetAllPayouts()
    {
        $json = <<<JSON
{
    "payouts": [
        {
            "totals": {
                "sale_amount": 500
            },
            "payment_reference": "XISA93DJ",
            "currency_code": "USD"
        }
    ],
    "pagination": {
        "count": 10,
        "total": 42
    }
}
JSON;
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $params = [
            'currency_code' => 'USD',
            'size' => 1
        ];
        $payout = new Payouts($this->connector);
        $data = $payout->getAllPayouts($params);

        $this->assertCount(1, $data['payouts']);
        $this->assertEquals(500, $data['payouts'][0]['totals']['sale_amount']);
        $this->assertEquals(10, $data['pagination']['count']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/payouts', $request->getUri()->getPath());
        $this->assertEquals('currency_code=USD&size=1', $request->getUri()->getQuery());
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testSummary()
    {
        $json = <<<JSON
[
    {
        "summary_total_fee_correction_amount": 550,
        "summary_payout_date_start": "2016-12-14T07:52:26Z",
        "summary_total_release_amount": 550
    },
    {
        "summary_total_fee_correction_amount": 100,
        "summary_payout_date_start": "2017-12-14T07:52:26Z",
        "summary_total_release_amount": 50
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

        $params = [
            'currency_code' => 'USD'
        ];
        $payout = new Payouts($this->connector);
        $data = $payout->getSummary($params);

        $this->assertCount(2, $data);
        $this->assertEquals(550, $data[0]['summary_total_fee_correction_amount']);
        $this->assertEquals(100, $data[1]['summary_total_fee_correction_amount']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/payouts/summary', $request->getUri()->getPath());
        $this->assertEquals('currency_code=USD', $request->getUri()->getQuery());
        $this->assertAuthorization($request);
    }
}
