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
 * File containing the Connector class.
 */

namespace Klarna\Rest\Transport;

use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Transport connector used to authenticate and make HTTP requests against the
 * Klarna APIs.
 */
class CURLConnector implements ConnectorInterface
{
    /**
     * Default request type
     */
    const DEFAULT_CONTENT_TYPE = 'application/json';

    /**
     * Extra CURL request options.
     */
    protected $options = [];

    /**
     * Merchant ID.
     *
     * @var string
     */
    protected $merchantId;

    /**
     * Shared secret.
     *
     * @var string
     */
    protected $sharedSecret;

    /**
     * Base URL.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * HTTP user agent.
     *
     * @var UserAgent
     */
    protected $userAgent;

    public function __construct(
        $merchantId,
        $sharedSecret,
        $baseUrl,
        UserAgentInterface $userAgent = null
    ) {
        $this->merchantId = $merchantId;
        $this->sharedSecret = $sharedSecret;
        $this->baseUrl = rtrim($baseUrl, '/');

        if ($userAgent === null) {
            $userAgent = UserAgent::createDefault();
        }
        $this->userAgent = $userAgent;
    }

    /**
     * Sets CURL request options.
     *
     * @param options CURL options
     * return self instance
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Sends HTTP GET request to specified path.
     *
     * @param path URL path
     * @param headers HTTP request headers
     * @return Processed response
     * @throws ConnectorException if API server returned non-20x HTTP CODE, Content-Type mismatched or response contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     */
    public function get($path, $headers = [])
    {
        return $this->request(Method::GET, $path, $headers);
    }

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
    public function post($path, $data = null, $headers = [])
    {
        return $this->request(Method::POST, $path, $headers, $data);
    }

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
    public function put($path, $data = null, $headers = [])
    {
        return $this->request(Method::PUT, $path, $headers, $data);
    }

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
    public function patch($path, $data = null, $headers = [])
    {
        return $this->request(Method::PATCH, $path, $headers, $data);
    }

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
    public function delete($path, $data = null, $headers = [])
    {
        return $this->request(Method::DELETE, $path, $headers, $data);
    }

    protected function request($method, $url, array $headers = [], $data = null)
    {
        $headers = array_merge([
           'Content-Type' => self::DEFAULT_CONTENT_TYPE,
           'User-Agent' => $this->userAgent,
        ], $headers);

        if (isset($this->options['headers'])) {
            $headers = array_merge($headers, $this->options['headers']);
        }

        $ch = curl_init();

        if (!empty($this->merchantId)) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->merchantId . ':' . $this->sharedSecret);
        }
        if (!empty($this->options['ssl_cert'])) {
            curl_setopt($ch, CURLOPT_SSLCERT, $this->options['ssl_cert']);
            if (!empty($this->options['ssl_key'])) {
                curl_setopt($ch, CURLOPT_SSLKEY, $this->options['ssl_key']);
            }
        }

        if (!empty($this->options['timeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method == Method::GET) {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        } elseif ($method == self::POST) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($proxy = getenv('HTTP_PROXY')) {
            $proxy = parse_url($proxy);

            $proxyHost = $proxy['host'];
            $proxyPort = $proxy['port'] ? ':' . $proxy['post'] : '';
            curl_setopt($ch, CURLOPT_PROXY, $proxyHost . $proxyPort);
            if (!empty($proxy['user'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['user'] . ':' . $proxy['pass']);
            }
        }

        $content = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if (!empty($content)) {
            while (strpos(ltrim($content), 'HTTP/') === 0) {
                list($headers, $content) = preg_split("/(\r?\n){2}/", $content, 2);
            }
        }

        if (!empty($error)) {
            // TODO: Process errors
        }

        return $content;
    }

    /**
     * Factory method to create a connector instance.
     *
     * @param string             $merchantId   Merchant ID
     * @param string             $sharedSecret Shared secret
     * @param string             $baseUrl      Base URL for HTTP requests
     * @param UserAgentInterface $userAgent    HTTP user agent to identify the client
     *
     * @return self
     */
    public static function create(
        $merchantId,
        $sharedSecret,
        $baseUrl = self::EU_BASE_URL,
        UserAgentInterface $userAgent = null
    ) {
        return new static($merchantId, $sharedSecret, $baseUrl, $userAgent);
    }
}
