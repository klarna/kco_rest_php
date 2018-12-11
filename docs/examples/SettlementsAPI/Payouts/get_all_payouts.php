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
 * Retrieve all Payouts.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

const DATE_FORMAT = 'Y-m-d\TH:m:s\Z';

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 */
$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $payouts = (new Klarna\Rest\Settlements\Payouts($connector))->getAllPayouts([
        'start_date' => (new DateTime('-1 year'))->format(DATE_FORMAT),
        'end_date' => (new DateTime())->format(DATE_FORMAT),
        'size' => 10,
    ]);

    print_r($payouts);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
