<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\Context\BaseContext;
use Behatch\HttpCall\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UnexpectedValueException;
use InvalidArgumentException;
use RuntimeException;

class UploadFileContext extends BaseContext
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var FeatureNode
     */
    private FeatureNode $currentFeature;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string    $method
     * @param string    $url
     * @param TableNode $data
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

            if ($this->isFileUpload($row['value'])) {
                $files[$row['key']] = $this->uploadFile(substr($row['value'],1));
            } else {
                $parameters[$row['key']] = $row['value'];
            }
        }

        return $this->request->send(
            $method,
            $this->locatePath($url),
            $parameters,
            $files
        );
    }

    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $this->currentFeature = $scope->getFeature();
    }

    /**
     * @param string $value
     * @return UploadedFile
     */
    private function uploadFile(string $value): UploadedFile
    {
        $absolutePath = $this->getAbsolutePathToUploadedFile(
            $value
        );

        $uploadedFilePath = tempnam(sys_get_temp_dir(), 'upload_');
        if (!copy($absolutePath, $uploadedFilePath)) {
            throw new RuntimeException('Can\'t copy file');
        }

        return new UploadedFile(
            $uploadedFilePath,
            basename($absolutePath),
            null,
            null,
            true
        );
    }

    /**
     * @param string $relativePath to feature file
     * @return string
     */
    private function getAbsolutePathToUploadedFile(string $relativePath): string
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
     * @return bool
     */
    private function isFileUpload($value): bool
    {
        return  is_string($value) && substr($value, 0, 1) == '@';
    }
}
