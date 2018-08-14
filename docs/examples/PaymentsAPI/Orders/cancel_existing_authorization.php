<?php
/**
 * Cancel an existing authorization
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$authorizationToken = getenv('AUTH_TOKEN') ?: 'authorizationToken';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $order = new Klarna\Rest\Payments\Orders($connector, $authorizationToken);
    $token = $order->cancelAuthorization();

    echo 'Authorization has been cancelled';

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
