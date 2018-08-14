<?php
/**
 * Create a checkout order.
 */

use Klarna\Rest\Transport\Exception\ConnectorException;

require_once dirname(__DIR__) . '/../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $checkout = new Klarna\Rest\Checkout\Order($connector);
    $checkout->create([
        'wrong order data'
    ]);
} catch (ConnectorException $e) {
    echo 'Message: ',  $e->getMessage(), "\n";
    echo 'Code: ',  $e->getCode(), "\n";
    echo 'CorrelationID: ',  $e->getCorrelationId(), "\n";
    echo 'ServiceVersion: ',  $e->getServiceVersion(), "\n";

} catch (Exception $e) {
    echo 'Unhandled exception: ',  $e->getMessage(), "\n";
}
