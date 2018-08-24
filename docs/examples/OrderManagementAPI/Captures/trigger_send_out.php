<?php
/**
 * Trigger a new send out of customer communication.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';
$captureId = getenv('CAPTURE_ID') ?: '34567';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);

    $capture = $order->fetchCapture($captureId);
    $capture->triggerSendout();

    echo 'Triggered a new send out of customer payment communication';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
