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
 * File containing the Tokens class.
 */

namespace Klarna\Rest\CustomerToken;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Tokens resource.
 */
class Tokens extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'customerToken';

    /**
     * {@inheritDoc}
     */
    public static $path = '/customer-token/v1/tokens';

    /**
     * Constructs a Tokens instance.
     *
     * @param Connector $connector HTTP transport connector
     * @param string    $customerToken   Customer Token
     */
    public function __construct(Connector $connector, $customerToken)
    {
        parent::__construct($connector);

        $this->setLocation(self::$path . "/{$customerToken}");
        $this[static::ID_FIELD] = $customerToken;
    }

    /**
     * Creates order using Customer Token
     *
     * @param array $data Order data
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return array created order data
     */
    public function createOrder(array $data)
    {
        return $this->post(self::$path, $data)
            ->status('200')
            ->contentType('application/json')
            ->getJson();
    }
}
