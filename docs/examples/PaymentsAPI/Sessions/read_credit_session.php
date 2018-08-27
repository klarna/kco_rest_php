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

try {
    $session = new Klarna\Rest\Payments\Sessions($connector, $sessionId);
    $session->fetch();

    print_r($session->getArrayCopy());

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
