<?php
/**
 * Copyright 2019 Klarna AB
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
 * File containing the TestCase class.
 */

namespace Klarna\Rest\Tests\Integration;

/**
 * Base unit test case class.
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $rootPath;
    protected $credentials = [];

    protected function hasCredentials()
    {
        return !empty($this->credentials);
    }

    /**
     * Sets up the test fixtures.
     */
    protected function setUp()
    {
        $this->rootPath = dirname(dirname(__DIR__));
        $path = getenv('CREDENTIALS');
        if ($path === false) {
            $path = $this->rootPath . '/credentials.json';
        }

        if (file_exists($path)) {
            $content = file_get_contents($path);
            $this->credentials = json_decode($content);

            foreach ($this->credentials as $field => $value) {
                $field = strtoupper($field);
                putenv("${field}=${value}");
            }
        }
    }

    protected function execFile($path)
    {
        ob_start();
        include $path;
        $value = ob_get_contents();
        ob_end_clean();

        return $value;
    }

    protected function hasException($output)
    {
        return preg_match('/Caught exception/ims', $output) === 1;
    }

    protected function isTextPresents($pattern, $output)
    {
        return preg_match("/${pattern}/ims", $output) === 1;
    }
}
