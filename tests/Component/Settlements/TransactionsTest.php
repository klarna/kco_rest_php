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
 * File containing tests for the Transactions class.
 */

namespace Klarna\Rest\Tests\Component\Settlements;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\Settlements\Transactions;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the token resource.
 */
class TransactionsTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testGetTransactions()
    {
        $json = <<<JSON
{
    "transactions": [
        {
            "amount": 2000,
            "capture_id": "33db6f16"
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

        $transactions = new Transactions($this->connector);
        $data = $transactions->getTransactions();

        $this->assertNotEmpty($data['transactions']);
        $this->assertEquals('33db6f16', $data['transactions'][0]['capture_id']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/settlements/v1/transactions', $request->getUri()->getPath());
        $this->assertAuthorization($request);
    }

    
}
