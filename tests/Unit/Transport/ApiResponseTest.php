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
 * File containing tests for the UserAgent class.
 */

namespace Klarna\Rest\Tests\Unit\Transport;

use PHPUnit\Framework\TestCase;
use Klarna\Rest\Transport\ApiResponse;

/**
 * Unit test cases for the UserAgent class.
 */
class ApiResponseTest extends TestCase
{
    /**
     * Make sure the default user agent components are present.
     *
     * @return void
     */
    public function testEmptyConstructor()
    {
        $r = new ApiResponse();
        $this->assertNull($r->getStatus());
        $this->assertNull($r->getBody());
        $this->assertEmpty($r->getHeaders());
    }

    public function testPropertyGetters()
    {
        $r = new ApiResponse(201, 'hello', ['Content-Type' => ['application/json']]);
        $this->assertEquals(201, $r->getStatus());
        $this->assertEquals('hello', $r->getBody());
        $this->assertEquals(['Content-Type' => ['application/json']], $r->getHeaders());
    }

    public function testHelpers()
    {
        $r = new ApiResponse(201, 'hello', [
            'Content-Type' => ['application/json'],
            'Location' => ['https://example.com/new-location']
        ]);
        $this->assertEquals(['application/json'], $r->getHeader('Content-Type'));
        $this->assertNull($r->getHeader('Content-Length'));

        $this->assertEquals('https://example.com/new-location', $r->getLocation());
    }
}
