<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the Transactions class.
 */

namespace Klarna\Rest\Settlements;

use GuzzleHttp\Exception\RequestException;
use Klarna\Exceptions\NotApplicableException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Transactions resource.
 *
 * @example docs/examples/SettlementsAPI/Transactions/get_transactions.php Returns a collection of transactions
 */
class Transactions extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $path = '/settlements/v1/transactions';

    /**
     * Constructs a Transactions instance.
     *
     * @param Connector $connector HTTP transport connector
     */
    public function __construct(Connector $connector)
    {
        parent::__construct($connector);
    }

    /**
     * Not applicable.
     *
     * @throws NotApplicableException
     */
    public function fetch()
    {
        throw new NotApplicableException('Not applicable');
    }

    /**
     * Returns a collection of transactions.
     *
     * @param array $params Additional query params to filter transactions.
     *
     * @see https://developers.klarna.com/api/#settlements-api-get-transactions
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return array Transactions data
     */
    public function getTransactions(array $params = [])
    {
        return $this->get(self::$path . '?' . http_build_query($params))
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }
}
