<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $segment = [];

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->apiContext = $environment->getContext('ApiContext');
    }

    /**
     * @Given I create segment witch name :name
     *
     * @param string $name
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iCreateSegment(string $name): void
    {
        $this->apiContext->post(
            sprintf('/api/v1/%s/segments', $this->apiContext->getLanguage()),
            [
                'name' => $name,
            ],
            $this->apiContext->getToken()
        );
        $content = $this->apiContext->getContent();
        if (isset($content['id'])) {
            $this->id = $this->apiContext->getContent()['id'];
        }
    }

    /**
     * @Given I get segment :id
     * @Given I get added segment
     *
     * @param string|null $id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iGetSegment(string $id = null): void
    {
        $id = $id?:$this->getSegmentId();

        $this->apiContext->get(
            sprintf('/api/v1/%s/segments/%s', $this->apiContext->getLanguage(), $id),
            $this->apiContext->getToken()
        );
        $this->segment = $this->apiContext->getContent();
    }

    /**
     * @Then I got :value in segment :property property
     *
     * @param string $value
     * @param string $property
     */
    public function iGotValueInProperty(string $value, string $property): void
    {
        TestCase::assertArrayHasKey($property, $this->segment, sprintf('Property "%s" not exists, available: "%s"', $property, implode(', ', array_keys($this->segment))));
        TestCase::assertEquals($value, $this->segment[$property]);
    }

    /**
     * @return string
     */
    public function getSegmentId(): string
    {
        return $this->id;
    }
}
