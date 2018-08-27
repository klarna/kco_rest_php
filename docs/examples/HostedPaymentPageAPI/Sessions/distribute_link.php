<?php
/**
 * Distribute link to the HPP session.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$sessionId = getenv('SESSION_ID') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $data = [
        "contact_information" => [
            "email" => "test@example.com",
            "phone" => "07000212345",
            "phone_country" => "SE"
        ],
        "method" => "sms",
        "template" => "INSTORE_PURCHASE"
    ];

    $session = new Klarna\Rest\HostedPaymentPage\Sessions($connector, $sessionId);
    $session->distributeLink($data);

    echo 'The session link has been distributed';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
