<?php
/**
 * Read an existing credit session.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$sessionId = getenv('SESSION_ID') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$order = [
    "purchase_country" => "gb",
    "purchase_currency" => "gbp",
    "locale" => "en-gb",
    "order_amount" => 4000,
    "order_tax_amount" => 800,
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "543670",
            "name" => "New updated bananas",
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
    $session = new Klarna\Rest\Payments\Sessions($connector, $sessionId);
    $session->update($order);

    echo 'Order has been updated';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
