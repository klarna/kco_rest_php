<?php
/**
 * Retrieve a checkout order.
 */

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $checkout = new Klarna\Rest\Checkout\Order($connector, $orderId);
    $checkout->fetch();

    // Get some data if needed
    echo <<<ORDER
         OrderID: $checkout[order_id]
    Order status: $checkout[status]
    HTML snippet: $checkout[html_snippet]
ORDER;

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
