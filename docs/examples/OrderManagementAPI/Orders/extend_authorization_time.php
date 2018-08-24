<?php
/**
 * Extend the order's authorization by default period according to merchant contract.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);
    $order->extendAuthorizationTime();

    echo 'The expiry time of an order has been extend';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
