<?php
/**
 * Get HPP session status.
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
    $session = new Klarna\Rest\HostedPaymentPage\Sessions($connector, $sessionId);
    $status = $session->getSessionStatus();

    print_r($status);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
