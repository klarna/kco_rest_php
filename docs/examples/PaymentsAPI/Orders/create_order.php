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
 * Create a new order.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 */
$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$authorizationToken = getenv('AUTH_TOKEN') ?: 'authorizationToken';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$address = [
    "given_name" => "John",
    "family_name" => "Doe",
    "email" => "johndoe@example.com",
    "title" => "Mr",
    "street_address" => "13 New Burlington St",
    "street_address2" => "Apt 214",
    "postal_code" => "W13 3BG",
    "city" => "London",
    "region" => "",
    "phone" => "01895808221",
    "country" => "GB"
];

$data = [
    "billing_address" => $address,
    "shipping_address" => $address,
    "purchase_country" => "GB",
    "purchase_currency" => "GBP",
    "locale" => "en-GB",
    "order_amount" => 10000,
    "order_tax_amount" => 2000,
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "123050",
            "name" => "Tomatoes",
            "quantity" => 10,
            "quantity_unit" => "kg",
            "unit_price" => 600,
            "tax_rate" => 2500,
            "total_amount" => 6000,
            "total_tax_amount" => 1200
        ],
        [
            "type" => "physical",
            "reference" => "543670",
            "name" => "Bananas",
            "quantity" => 1,
            "quantity_unit" => "bag",
            "unit_price" => 5000,
            "tax_rate" => 2500,
            "total_amount" => 4000,
            "total_discount_amount" => 1000,
            "total_tax_amount" => 800
        ]
    ]
];

try {
    $order = new Klarna\Rest\Payments\Orders($connector, $authorizationToken);
    $data = $order->create($data);

    print_r($data);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
