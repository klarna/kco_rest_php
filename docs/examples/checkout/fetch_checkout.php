<?php
/**
 * Retrieve a checkout order.
 */

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderUrl = getenv('ORDER_URL') ?: 'https://playground.api.klarna.com/checkout/v3/orders/12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::TEST_BASE_URL
);

$checkout = new Klarna\Rest\Checkout\Order($connector, $orderUrl);
$checkout->fetch();
