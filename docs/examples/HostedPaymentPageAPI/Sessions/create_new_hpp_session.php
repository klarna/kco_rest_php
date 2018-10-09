<?php
/**
 * Create a new HPP session.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
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
