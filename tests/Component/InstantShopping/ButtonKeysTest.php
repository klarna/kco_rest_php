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
 * File containing tests for the ButtonKeys class.
 */

namespace Klarna\Rest\Tests\Component\InstantShopping;

use GuzzleHttp\Psr7\Response;
use Klarna\Rest\InstantShopping\ButtonKeys;
use Klarna\Rest\Tests\Component\ResourceTestCase;

/**
 * Component test cases for the Instant Shopping Button Keys resource.
 */
class ButtonKeysTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testCreate()
    {
        $json = <<<JSON
{
    "button_key": "123-key",
    "merchant_logo": "https://example.com/inage.jpg",
    "validFrom": "10/03/2019",
    "disabled": true
}
JSON;

        $this->mock->append(
            new Response(
                201,
                [
                    'Content-Type' => 'application/json',
                    'Location' => 'https://example.com/some-url',
                ],
                $json
            )
        );

        $button = new ButtonKeys($this->connector);

        $data = $button->create([
            'data' => 'sent in'
        ]);

        $this->assertEquals('123-key', $data['button_key']);
        $this->assertTrue($data['disabled']);

        $request = $this->mock->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/instantshopping/v1/buttons', $request->getUri()->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating.
     *
     * @return void
     */
    public function testUpdate()
    {
        $json = <<<JSON
{
    "button_key": "123-key",
    "merchant_logo": "https://example.com/inage.jpg",
    "validFrom": "10/03/2019",
    "disabled": true
}
JSON;
        
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $button = new ButtonKeys($this->connector, 'button-id-123456');
        $data = $button->update(['data' => 'sent in']);

        $this->assertEquals('123-key', $data['button_key']);
        $this->assertTrue($data['disabled']);
        
        $request = $this->mock->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals(
            '/instantshopping/v1/buttons/button-id-123456',
            $request->getUri()->getPath()
        );
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));
        

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when retrieving.
     *
     * @return void
     */
    public function testRetrieve()
    {
        $json = <<<JSON
{
    "button_key": "123-key",
    "merchant_logo": "https://example.com/inage.jpg",
    "validFrom": "10/03/2019",
    "disabled": true
}
JSON;
        
        $this->mock->append(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                $json
            )
        );

        $button = new ButtonKeys($this->connector, 'button-id-123456');
        $button->retrieve();

        $this->assertEquals('123-key', $button['button_key']);
        $this->assertEquals('123-key', $button->getId());
        $this->assertTrue($button['disabled']);
        
        $request = $this->mock->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(
            '/instantshopping/v1/buttons/button-id-123456',
            $request->getUri()->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRetrieveException()
    {
        $button = new ButtonKeys($this->connector);
        $button->retrieve();
    }
}
