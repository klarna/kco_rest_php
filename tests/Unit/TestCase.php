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
 * File containing the TestCase class.
 */

namespace Klarna\Rest\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\ApiResponse;

/**
 * Base unit test case class.
 */
class TestCase extends PHPUnitTestCase
{
    /**
     */
    protected $response;

    protected $request;

    /**
     * @var \Klarna\Rest\Transport\ConnectorInterface
     */
    protected $connector;

    /**
     * Sets up the test fixtures.
     */
    protected function setUp()
    {
        $this->response = $this->getMockBuilder(ApiResponse::class)
            ->getMock();

        $this->connector = $this->getMockBuilder(Connector::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
