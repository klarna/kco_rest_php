<?php
/**
 * Gets PDF payout summary report
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
    $report = $reports->getPDFPayoutReport($paymentReference);

    file_put_contents('report.pdf', $report);
    echo 'Saved to report.pdf';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
