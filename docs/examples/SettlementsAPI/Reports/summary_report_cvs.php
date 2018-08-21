<?php
/**
 * Gets a CSV payout summary report.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

const DATE_FORMAT = 'Y-m-d\TH:m:s\Z';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

try {
    $reports = new Klarna\Rest\Settlements\Reports($connector);
    $report = $reports->getCSVPayoutsSummaryReport([
        'start_date' => (new DateTime('-1 year'))->format(DATE_FORMAT),
        'end_date' => (new DateTime())->format(DATE_FORMAT)
    ]);

    file_put_contents('summary_report.csv', $report);
    echo 'Saved to summary_report.csv';

} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
