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
 * File containing the ResponseValidator class.
 */

namespace Klarna\Rest\Transport;

use Psr\Http\Message\ResponseInterface;

/**
 * HTTP response validator helper class.
 */
class ResponseValidator
{
    /**
     * HTTP response to validate against.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Constructs a response validator instance.
     *
     * @param ResponseInterface $response Response to validate
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Gets the response object.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Asserts the HTTP response status code.
     *
     * @param string|string[] $status Expected status code(s)
     *
     * @throws \RuntimeException If status code does not match
     *
     * @return self
     */
    public function status($status)
    {
        $httpStatus = (string) $this->response->getStatusCode();
        if (is_array($status) && !in_array($httpStatus, $status)) {
            throw new \RuntimeException(
                "Unexpected response status code: {$httpStatus}"
            );
        }

        if (is_string($status) && $httpStatus !== $status) {
            throw new \RuntimeException(
                "Unexpected response status code: {$httpStatus}"
            );
        }

        return $this;
    }

    /**
     * Asserts the Content-Type header. Checks partial matching.
     * Validation PASSES in the following cases:
     *      Content-Type: application/json
     *      $mediaType = 'application/json'
     *
     *      Content-Type: application/json; charset=utf-8
     *      $mediaType = 'application/json'
     *
     * Validation FAILS in the following cases:
     *      Content-Type: plain/text
     *      $mediaType = 'application/json'
     *
     *      Content-Type: application/json; charset=utf-8
     *      $mediaType = 'application/json; charset=cp-1251'
     *
     * @param string $mediaType Expected media type. RegExp rules can be used.
     *
     * @throws \RuntimeException If Content-Type header is missing
     * @throws \RuntimeException If Content-Type header does not match
     *
     * @return self
     */
    public function contentType($mediaType)
    {
        if (!$this->response->hasHeader('Content-Type')) {
            throw new \RuntimeException('Response is missing a Content-Type header');
        }

        $contentType = $this->response->getHeader('Content-Type');
        $mediaFound = false;
        foreach ($contentType as $type) {
            if (preg_match('#' . $mediaType . '#', $type)) {
                $mediaFound = true;
                break;
            }
        }

        if (!$mediaFound) {
            throw new \RuntimeException(
                'Unexpected Content-Type header received: ' . implode(',', $contentType) . '. Expected: ' . $mediaType
            );
        }

        return $this;
    }

    /**
     * Gets the decoded JSON response.
     *
     * @throws \RuntimeException         If the response body is not in JSON format
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     *
     * @return array
     */
    public function getJson()
    {
        return \json_decode($this->response->getBody(), true);
    }

    /**
     * Gets response body.
     *
     * @throws \RuntimeException         If the response body is not in JSON format
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     *
     * @return StreamInterface the body as a stream
     */
    public function getBody()
    {
        return $this->response->getBody();
    }

    /**
     * Gets the Location header.
     *
     * @throws \RuntimeException If the Location header is missing
     *
     * @return string
     */
    public function getLocation()
    {
        if (!$this->response->hasHeader('Location')) {
            throw new \RuntimeException('Response is missing a Location header');
        }

        return $this->response->getHeader('Location')[0];
    }
}
