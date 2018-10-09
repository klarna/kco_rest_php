<?php
/**
 * Generate a consumer token.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$authorizationToken = getenv('AUTH_TOKEN') ?: 'authorizationToken';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$data = [
    "purchase_country" => "GB",
    "purchase_currency" => "GBP",
    "locale" => "en-GB",
    "billing_address" => [
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
    ],
    "customer" => [ // MUST MATCH line by line to the customer details that was used to get an Authorization Token
        "date_of_birth" => "1970-01-01",
        "gender" => "male",
    ],
    "description" => "For testing purposes",
    "intended_use" => "SUBSCRIPTION"
];

try {
    $order = new Klarna\Rest\Payments\Orders($connector, $authorizationToken);
    $token = $order->generateToken($data);

    print_r($token);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
