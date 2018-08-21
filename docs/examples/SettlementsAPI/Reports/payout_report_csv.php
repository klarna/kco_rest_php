<?php
/**
 * Gets a CSV payout report with all transactions.
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
    $reports = new Klarna\Rest\Settlements\Reports($connector);
    $report = $reports->getCSVPayoutReport($paymentReference);

    file_put_contents('report.csv', $report);
    echo 'Saved to report.csv';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
