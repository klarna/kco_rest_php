<?php
/**
 * Cancel an authorized order.
 *
 * For a cancellation to be successful, there must be no captures on the order.
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
    $order->cancel();

    echo 'Order has been cancelled';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
