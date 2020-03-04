<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

use Assert\Assertion;
use Assert\AssertionFailedException as AssertionFailure;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Imbo\BehatApiExtension\Exception\AssertionFailedException;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Psr7;

/**
 */
class ApiContext extends \Imbo\BehatApiExtension\Context\ApiContext
{
    private const JSON_CONTENT = 'application/json';

    /**
     * @var StorageContext
     */
    private $storageContext;

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->storageContext = $environment->getContext('StorageContext');
    }

    /**
     * @param string $key
     * @param string $var
     *
     * @Then remember response param :key as :var
     */
    public function rememberResponseParam(string $key, string $var): void
    {
        $response = $this->getResponseBody();

        if (!isset($response->{$key})) {
            throw new \RuntimeException(sprintf(
                'Key "%s" not found in response "%s"',
                $key,
                $this->response->getBody()
            ));
        }

        $this->storageContext->add($var, $response->{$key});
    }

    /**
     * @param string $keys
     *
     * @throws AssertionFailedException
     *
     * @Then the JSON object contains keys :keys
     */
    public function assertJsonObjectContainsKeys(string $keys): void
    {
        $this->requireResponse();
        $body = $this->getResponseBody();
        $keysCollection = explode(',', $keys);

        try {
            foreach ($keysCollection as $key) {
                Assertion::propertyExists($body, $key);
            }
        } catch (AssertionFailure $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then not found response is received
     */
    public function assertResponseNotFound(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_NOT_FOUND);
        $this->assertJsonObjectContainsKeys('code,message');
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then created response is received
     */
    public function assertResponseCreated(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_CREATED);
        $this->assertJsonObjectContainsKeys('id');
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then validation error response is received
     */
    public function assertResponseValidationError(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_BAD_REQUEST);
        $this->assertJsonObjectContainsKeys('code,message,errors');
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then empty response is received
     */
    public function assertResponseEmpty(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then access denied response is received
     */
    public function assertResponseAccessDenied(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_FORBIDDEN);
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then unauthorized response is received
     */
    public function assertResponseUnauthorized(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then conflict response is received
     */
    public function assertResponseConflict(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_CONFLICT);
        $this->assertJsonObjectContainsKeys('code,message');
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then not implemented response is received
     */
    public function assertResponseNotImplemented(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_NOT_IMPLEMENTED);
        $this->assertJsonObjectContainsKeys('code,message');
    }

    /**
     * @throws AssertionFailedException
     *
     * @Then grid response is received
     */
    public function assertResponseGrid(): void
    {
        $this->requireResponse();
        $this->assertResponseCodeIs(Response::HTTP_OK);
        $this->assertJsonObjectContainsKeys('configuration,columns,collection,info');
    }

    /**
     * @Then /^print last api response$/
     */
    public function printLastApiResponse(): void
    {
        $this->requireResponse();
    }

    /**
     * @Then /^sleep$/
     */
    public function sleep(): void
    {
        sleep(1);
    }

    /**
     * {@inheritDoc}
     */
    public function assertResponseCodeIs($code): void
    {
        try {
            $actual = $this->response->getStatusCode();
            $expected = $this->validateResponseCode($code);

            Assertion::same($actual, $expected);
        } catch (\Exception $e) {
            throw new AssertionFailedException($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function requestPath($path, $method = null)
    {
        $path = $this->storageContext->replaceVars($path);

        $this->setRequestHeader('Accept', self::JSON_CONTENT);

        parent::requestPath($path, $method);
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestBody($string)
    {
        $string = $this->storageContext->replaceVars($string);

        if ($this->isJson($string)) {
            $this->setRequestHeader('Content-Type', self::JSON_CONTENT);
        }

        return parent::setRequestBody($string);
    }

    /**
     * {@inheritDoc}
     */
    public function setRequestFormParams(TableNode $table): void
    {
        $data = $table->getTable();
        foreach ($data as $rowKey => $row) {
            foreach ($row as $columnKey => $column) {
                $data[$rowKey][$columnKey] = $this->storageContext->replaceVars($column);
            }
        }

        parent::setRequestFormParams(new TableNode($data));
    }

    /**
     * @return array|mixed|stdClass
     */
    public function getLastResponseBody()
    {
        return $this->getResponseBody();
    }

    /**
     */
    public function requestSend(): void
    {
        $this->sendRequest();
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponseBody()
    {
        $source = (string) $this->response->getBody();
        $body = json_decode($source, false);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException(sprintf(
                'The response body does not contain valid JSON data. Received "%s"',
                $source
            ));
        }
        if (!is_array($body) && !($body instanceof stdClass)) {
            throw new InvalidArgumentException(sprintf(
                'The response body does not contain a valid JSON array / object. Received "%s"',
                $source
            ));
        }

        return $body;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponseBodyArray()
    {
        if (!is_array($body = $this->getResponseBody())) {
            throw new InvalidArgumentException(sprintf(
                'The response body does not contain a valid JSON array. Received "%s"',
                $this->response->getBody()
            ));
        }

        return $body;
    }

    /**
     * @param $resource
     * @return bool
     */
    private function isJson($resource)
    {
        $string = (string) Psr7\stream_for($resource);
        if (!$string) {
            return false;
        }
        json_decode($string);

        return JSON_ERROR_NONE === json_last_error();
    }
}
