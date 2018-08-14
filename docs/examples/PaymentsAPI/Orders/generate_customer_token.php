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
    "purchase_country" => "gb",
    "purchase_currency" => "gbp",
    "locale" => "en-gb",
    "billing_address" => [
        "given_name" => "John",
        "family_name" => "Doe",
        "email" => "john@doe.com",
        "title" => "Mr",
        "street_address" => "13 New Burlington St",
        "street_address2" => "Apt 214",
        "postal_code" => "W13 3BG",
        "city" => "London",
        "region" => "",
        "phone" => "01895808221",
        "country" => "GB"
    ],
    "customer" => [
        "date_of_birth" => "1986-08-08T",
        "title" => "Mr. Jophn Doe",
        "gender" => "M",
        "last_four_ssn" => "1234",
        "national_identification_number" => "1234123412341234",
        "type" => "A",
        "vat_id" => "12345",
        "organization_registration_id" => "12345",
        "organization_entity_type" => "LIMITED_COMPANY"
    ],
    "description" => "For testing purposes",
    "intended_use" => "SUBSCRIPTION"
];

try {
    $order = new Klarna\Rest\Payments\Orders($connector, $authorizationToken);
    $token = $order->generateToken($data);

    print_r($token);

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
