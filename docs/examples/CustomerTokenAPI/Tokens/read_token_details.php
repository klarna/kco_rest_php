<?php
/**
 * Read customer tokens details
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$customerToken = getenv('TOKEN') ?: 'customerToken';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $token = new Klarna\Rest\CustomerToken\Tokens($connector, $customerToken);
    $token->fetch();

    print_r($token->getArrayCopy());

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
