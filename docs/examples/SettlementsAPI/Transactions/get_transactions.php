<?php
/**
 * Retrieve all transactions.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $transactions = new Klarna\Rest\Settlements\Transactions($connector);
    $data = $transactions->getTransactions();

    print_r($data);

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
