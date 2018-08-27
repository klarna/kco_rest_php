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
 * File containing tests for the Reports class.
 */

namespace Klarna\Rest\Tests\Component\Settlements;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Settlements\Reports;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the token resource.
 */
class ReportsTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetCSVPayoutReport()
    {
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'text/csv'],
                "A;B;C\n1;2;3"
            )
        );

        $reports = new Reports($this->connector);
        $data = $reports->getCSVPayoutReport('reference-123');

        $this->assertEquals("A;B;C\n1;2;3", $data);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/reports/payout-with-transactions', $request->getUri()->getPath());
        $this->assertEquals('payment_reference=reference-123', $request->getUri()->getQuery());
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetPDFPayoutReport()
    {
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/pdf'],
                "123412341234"
            )
        );

        $reports = new Reports($this->connector);
        $data = $reports->getPDFPayoutReport('reference-123');

        $this->assertEquals("123412341234", $data);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/reports/payout', $request->getUri()->getPath());
        $this->assertEquals('payment_reference=reference-123', $request->getUri()->getQuery());
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetCSVPayoutsSummaryReport()
    {
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'text/csv'],
                "A;B;C\n1;2;3"
            )
        );

        $params = [
            'start_date' => '2017-08-17T11:08:18Z',
            'end_date' => '2018-09-17T11:08:18Z'
        ];
        $reports = new Reports($this->connector);
        $data = $reports->getCSVPayoutsSummaryReport($params);

        $this->assertEquals("A;B;C\n1;2;3", $data);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/reports/payouts-summary-with-transactions', $request->getUri()->getPath());
        $this->assertEquals(
            'start_date=2017-08-17T11%3A08%3A18Z&end_date=2018-09-17T11%3A08%3A18Z',
            $request->getUri()->getQuery()
        );
        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetPDFPayoutsSummaryReport()
    {
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/pdf'],
                "123124312341234"
            )
        );

        $params = [
            'start_date' => '2017-08-17T11:08:18Z',
            'end_date' => '2018-09-17T11:08:18Z'
        ];
        $reports = new Reports($this->connector);
        $data = $reports->getPDFPayoutsSummaryReport($params);

        $this->assertEquals("123124312341234", $data);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/reports/payouts-summary', $request->getUri()->getPath());
        $this->assertEquals(
            'start_date=2017-08-17T11%3A08%3A18Z&end_date=2018-09-17T11%3A08%3A18Z',
            $request->getUri()->getQuery()
        );
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

        $reports = new Reports($this->connector);
        $reports->fetch();
    }
}
