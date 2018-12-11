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
 * Create a new HPP session.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';
$sessionId = getenv('SESSION_ID') ?: 'sessionId';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$session = [
    "merchant_urls" => [
        "cancel" => "https://example.com/cancel",
        "failure" => "https://example.com/fail",
        "privacy_policy" => "https://example.com/privacy_policy",
        "success" => "https://example.com/success?token={{authorization_token}}",
        "terms" => "https://example.com/terms"
    ],
    "options" => [
        "background_images" => [
            [
                "url" => "https://example.com/bgimage.jpg",
                "width" => 1200
            ]
        ],
        "logo_url" => "https://example.com/logo.jpg",
        "page_title" => "Complete your purchase",
        "payment_method_category" => "pay_later",
        "purchase_type" => "buy"
    ],
    "payment_session_url" => "https://api.klarna.com/payments/v1/sessions/$sessionId"
];

try {
    $hpp = new Klarna\Rest\HostedPaymentPage\Sessions($connector);
    $sessionData = $hpp->create($session);

    print_r($sessionData);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
