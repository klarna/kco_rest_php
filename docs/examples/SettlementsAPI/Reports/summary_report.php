<?php
/**
 * Gets payout summary report.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

const EMD_FORMAT = 'Y-m-d\TH:m:s\Z';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $reports = new Klarna\Rest\Settlements\Reports($connector);
    $report = $reports->getPayoutsSummaryReport([
        'start_date' => (new DateTime('-1 year'))->format(EMD_FORMAT),
        'end_date' => (new DateTime())->format(EMD_FORMAT)
    ]);

    echo $report;

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
