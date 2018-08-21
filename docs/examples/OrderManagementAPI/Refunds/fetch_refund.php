<?php
/**
 * Retrieve a refund.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';
$refundId = getenv('REFUND_ID') ?: '34567';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);
    $capture = $order->fetchRefund($refundId);

    print_r($capture->getArrayCopy());

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
