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
 * File containing tests for the abstract Resource class.
 */

namespace Klarna\Rest\Tests\Unit;

use GuzzleHttp\ClientInterface;
use Klarna\Rest\Tests\Unit\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\UserAgent;

/**
 * Unit test cases for the resource class.
 */
class ResourceTest extends TestCase
{
    const USERNAME = '1234';

    const PASSWORD = 'MySecret';

    const BASE_URL = 'http://base-url.internal.machines';

    const PATH = '/test/url';

    /**
     * @var Connector
     */
    protected $connector;

    /**
     * @var \Klarna\Rest\Transport\UserAgentInterface
     */
    protected $userAgent;

    /**
     * Set up the test fixtures.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->getMockBuilder(ClientInterface::class)
            ->getMock();

        $this->userAgent = $this->getMockBuilder(UserAgent::class)
            ->getMock();

        $this->userAgent->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue('a-user-agent'));

        $this->connector = new Connector(
            $this->client,
            self::USERNAME,
            self::PASSWORD,
            $this->userAgent
        );
    }

    /**
     * Set up the test fixtures.
     */
    protected function tearDown()
    {
        parent::tearDown();

        putenv('DEBUG_SDK=');
    }


    /**
     * Make sure that getting id function returns proper values.
     *
     * @return void
     */
    public function testGetId()
    {
        $r = $this->getMockForAbstractClass('Klarna\Rest\Resource', [$this->connector]);
        $this->assertEquals(null, $r->getId());
        $r['id'] = 'test';
        $this->assertEquals('test', $r->getId());
    }

    /**
     * Make sure that setting and getting location function returns proper values.
     *
     * @return void
     */
    public function testLocation()
    {
        $r = $this->getMockForAbstractClass('Klarna\Rest\Resource', [$this->connector]);
        $this->assertEquals(null, $r->getLocation());

        $r->setLocation('/new/location');
        $this->assertEquals('/new/location', $r->getLocation());
    }
}
