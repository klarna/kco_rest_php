<?php
/**
 * Retrieve a Payouts summary.
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
    $payouts = new Klarna\Rest\Settlements\Payouts($connector);
    $summary = $payouts->getSummary([
        'start_date' => (new DateTime('-1 year'))->format(EMD_FORMAT),
        'end_date' => (new DateTime())->format(EMD_FORMAT)
    ]);

    print_r($summary);

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
