<?php
/**
 * Copyright 2019 Klarna AB
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
 */

namespace Klarna\Tests\Integration\Checkout;

use Klarna\Rest\Tests\Integration\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Integration test cases for the checkout order resource.
 */
class OrderTest extends TestCase
{
    public function testCreateCheckout()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }

        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/create_checkout.php');

        $this->assertFalse($this->hasException($output));
        $this->assertTrue($this->isTextPresents('OrderID:[ a-zA-Z0-9-]+', $output), 'No OrderID found');
        $this->assertTrue($this->isTextPresents('Order status:[ a-z]+', $output), 'No Order Status found');
    }

    public function testFetchNonExisting()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }
        putenv('ORDER_ID=12345');
        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/fetch_checkout.php');
        $this->assertTrue($this->hasException($output));
        $this->assertTrue($this->isTextPresents('404 Not Found', $output));
    }

    public function testCreateAndFetch()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }

        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/create_checkout.php');
        $this->assertTrue($this->isTextPresents('OrderID:[ a-zA-Z0-9-]+', $output), 'No OrderID found');

        preg_match('/OrderID: ([a-zA-Z0-9-]+)/ims', $output, $matches);
        $orderId = $matches[1];

        putenv('ORDER_ID=' . $orderId);
        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/fetch_checkout.php');
        $this->assertFalse($this->hasException($output));
        $this->assertTrue($this->isTextPresents('OrderID:[ a-zA-Z0-9-]+', $output), 'No OrderID found');
        $this->assertTrue($this->isTextPresents('Order status:[ a-z]+', $output), 'No Order Status found');
    }

    public function testCreateAndUpdate()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }

        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/create_checkout.php');
        $this->assertTrue($this->isTextPresents('OrderID:[ a-zA-Z0-9-]+', $output), 'No OrderID found');

        preg_match('/OrderID: ([a-zA-Z0-9-]+)/ims', $output, $matches);
        $orderId = $matches[1];

        putenv('ORDER_ID=' . $orderId);
        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/update_checkout.php');
        $this->assertFalse($this->hasException($output));
        $this->assertTrue($this->isTextPresents('Order has been successfully updated', $output));
        $this->assertTrue($this->isTextPresents('New Order Amount: 11000', $output));
        $this->assertTrue($this->isTextPresents('New Order Tax: 2200', $output));
    }

    public function testHandlingExceptions()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }

        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/create_checkout.php');
        $this->assertTrue($this->isTextPresents('OrderID:[ a-zA-Z0-9-]+', $output), 'No OrderID found');

        preg_match('/OrderID: ([a-zA-Z0-9-]+)/ims', $output, $matches);
        $orderId = $matches[1];

        putenv('ORDER_ID=' . $orderId);
        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/handling_exceptions.php');
        
        $this->assertTrue($this->isTextPresents('BAD_REQUEST', $output));
        $this->assertTrue($this->isTextPresents('Code: 400', $output));
        $this->assertTrue($this->isTextPresents('CorrelationID:[ a-zA-Z0-9-]+', $output));
    }

    public function testHandlingExceptionsUnauthorized()
    {
        if (!$this->hasCredentials()) {
            return $this->markTestSkipped('No credentials provided');
        }

        putenv('PASSWORD=wrong_password');
        $output = $this->execFile($this->rootPath . '/docs/examples/CheckoutAPI/handling_exceptions.php');
        
        $this->assertTrue($this->isTextPresents('401 Unauthorized', $output));
    }
}
