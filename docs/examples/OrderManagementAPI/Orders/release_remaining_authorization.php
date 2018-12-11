<?php
/**
 * Copyright 2018 Klarna AB
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

/**
 * Release the remaining authorization for an order.
 *
 * Signal that there is no intention to perform further captures.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);
    $order->releaseRemainingAuthorization();
    
    echo 'Remaining authorised amount has been released';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
