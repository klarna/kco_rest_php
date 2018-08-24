<?php
/**
 * Retrieve a Payout info.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$paymentReference = getenv('PAYMENT_REFERENCE') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $payouts = new Klarna\Rest\Settlements\Payouts($connector);
    $payout = $payouts->getPayout($paymentReference);

    print_r($payout);

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
