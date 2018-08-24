<?php
/**
 * Release the remaining authorization for an order.
 *
 * Signal that there is no intention to perform further captures.
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
    $order->releaseRemainingAuthorization();
    
    echo 'Remaining authorised amount has been released';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
