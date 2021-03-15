<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\Context\BaseContext;
use Behatch\HttpCall\Request;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UnexpectedValueException;
use function GuzzleHttp\Psr7\mimetype_from_filename;

/**
 * Uploads files and form params in behat, path to files are relative to the feature file.
 * And the path to the file must have an @ character at the beginning.
 *
 * @example
 *  Scenario: Upload image
 *      Given I am Authenticated as "test@ergonode.com"
 *      And I add "Content-Type" header equal to "multipart/form-data"
 *      And I add "Accept" header equal to "application/json"
 *      When I send a POST request to "/api/v1/multimedia/upload" with params:
 *        | key         | value              |
 *        | upload      | @image/test.jpg    |
 *        | upload2     | @image/test.jpg    |
 *        | form_param1 | other form param1  |
 *        | form_param2 | other form param2  |
 *      Then the response status code should be 201
 *      And the JSON node "id" should exist
 */
class UploadFileContext extends BaseContext
{
    private Request $request;

    private FeatureNode $currentFeature;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     *
     * @Given I send a :method request to :url with params:
     */
    public function iSendARequestToWithParams(string $method, string $url, TableNode $data)
    {
        $files = [];
        $parameters = [];

        foreach ($data->getHash() as $row) {
            if (!isset($row['key']) || !isset($row['value'])) {
                throw new InvalidArgumentException("You must provide a 'key' and 'value' column in your table node.");
            }

            if ($this->isTestFileUpload($row['value'])) {
                $files[$row['key']] = $this->uploadTestFile(substr($row['value'], 1));
            } else {
                $parameters[$row['key']] = $row['value'];
            }
        }

        return $this->request->send(/* @phpstan-ignore-line */
            $method,
            $this->locatePath($url),
            $parameters,
            $files
        );
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario(BeforeScenarioScope $scope): void
    {
        $this->currentFeature = $scope->getFeature();
    }

    private function uploadTestFile(string $value): UploadedFile
    {
        $testFilePath = $this->getAbsolutePathToTestFile(
            $value
        );

        $uploadedFilePath = tempnam(sys_get_temp_dir(), 'upload_');
        if (!copy($testFilePath, $uploadedFilePath)) {
            throw new RuntimeException('Can\'t copy file');
        }

        return new UploadedFile(
            $uploadedFilePath,
            basename($testFilePath),
            mimetype_from_filename($testFilePath),
            null,
            true
        );
    }

    /**
     * @param string $relativePath to feature file
     */
    private function getAbsolutePathToTestFile(string $relativePath): string
    {
        $filePath = realpath($this->currentFeature->getFile());
        $directory = dirname($filePath);

        $path = $directory.DIRECTORY_SEPARATOR.$relativePath;
        $realpath = realpath($path);
        if (!is_string($realpath)) {
            throw new UnexpectedValueException('Expected string, probably path to file is bad');
        }

        return $realpath;
    }

    /**
     * @param mixed $value
     */
    private function isTestFileUpload($value): bool
    {
        return is_string($value) && substr($value, 0, 1) === '@';
    }
}
