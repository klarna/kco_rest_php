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
 * File containing the Connector interface.
 */

namespace Klarna\Rest\Transport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Transport\Exception\ConnectorException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP transport connector interface used to authenticate and make HTTP requests
 * against the Klarna APIs.
 *
 * The HTTP communication is handled by
 * {@link http://guzzle.readthedocs.org/en/guzzle4/ Guzzle}.
 */
interface ConnectorInterface
{
    /**
     * API base URL for Europe.
     */
    const EU_BASE_URL = 'https://api.klarna.com';

    /**
     * Testing API base URL for Europe.
     */
    const EU_TEST_BASE_URL = 'https://api.playground.klarna.com';

    /**
     * API base URL for North America.
     */
    const NA_BASE_URL = 'https://api-na.klarna.com';

    /**
     * Testing API base URL for North America.
     */
    const NA_TEST_BASE_URL = 'https://api-na.playground.klarna.com';

    /**
     * Sends HTTP GET request to specified path.
     *
     * @param path URL path.
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE, Content-Type mismatched or response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function get($path, $headers = []);

    /**
     * Sends HTTP POST request to specified path.
     *
     * @param path URL path.
     * @param data Data to be sent to API server in a payload.
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE and response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function post($path, $data = null, $headers = []);

    /**
     * Sends HTTP PUT request to specified path.
     *
     * @param path URL path.
     * @param data Data to be sent to API server in a payload.
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE and response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function put($path, $data = null, $headers = []);

    /**
     * Sends HTTP PATCH request to specified path.
     *
     * @param path URL path.
     * @param data Data to be sent to API server in a payload.
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE and response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function patch($path, $data = null, $headers = []);

    /**
     * Sends HTTP DELETE request to specified path.
     *
     * @param path URL path.
     * @param data Data to be sent to API server in a payload.
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE and response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function delete($path, $data = null, $headers = []);
}
