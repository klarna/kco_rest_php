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
 * File containing the Instant Shopping Orders class.
 */

namespace Klarna\Rest\InstantShopping;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Resource;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Instant shopping Order resource.
 */
class Orders extends Resource
{
    /**
     * {@inheritDoc}
     */
    const ID_FIELD = 'authorization_token';

    /**
     * {@inheritDoc}
     */
    public static $path = '/instantshopping/v1/authorizations';

    /**
     * Constructs an Order instance.
     *
     * @param Connector $connector HTTP transport connector
     * @param string    $authorizationToken Authorization Token
     */
    public function __construct(Connector $connector, $authorizationToken)
    {
        parent::__construct($connector);

        $this->setLocation(self::$path . "/{$authorizationToken}");
        $this[static::ID_FIELD] = $authorizationToken;
    }

    /**
     * Retrieves an authorized order based on the authorization token.
     * 
     * @see https://developers.klarna.com/api/#instant-shopping-api-retrieves-an-authorized-order-based-on-the-authorization-token
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return self
     */
    public function retrieve()
    {
        return $this->fetch();
    }

    /**
     * Declines an authorized order identified by the authorization token.
     *
     * @param array $data Decline data
     * 
     * @see https://developers.klarna.com/api/#instant-shopping-api-declines-an-authorized-order-identified-by-the-authorization-token
     * 
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  If the location header is missing
     * @throws \RuntimeException  If the API replies with an unexpected response
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return self
     */
    public function decline(array $data = null)
    {
        $this->delete($this->getLocation(), $data)
            ->status('204');

        return $this;
    }

    /**
     * Approves the authorized order and places an order identified by the authorization token.
     *
     * @see https://developers.klarna.com/api/#instant-shopping-api-approve-the-authorized-order-and-place-an-order-identified-by-the-authorization-token
     *
     * @param array $data Order data
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return self
     */
    public function approve(array $data)
    {
        $this->post($this->getLocation() . '/orders', $data)
            ->status('200');

        return $this;
    }
}
