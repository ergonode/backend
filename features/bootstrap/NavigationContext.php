<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use PHPUnit\Framework\TestCase;

/**
 */
class NavigationContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

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
     * @Given I get user profile
     */
    public function iGetUserProfile(): void
    {
        $this->apiContext->get(
            '/api/v1/profile',
            $this->apiContext->getToken()
        );
    }

    /**
     * @Given I get user name :firstName :lastName
     *
     * @param string $firstName
     * @param string $lastName
     */
    public function iGetUserName(string $firstName, string $lastName): void
    {
        $result = $this->apiContext->getContent();

        TestCase::assertEquals($firstName, $result['first_name']);
        TestCase::assertEquals($lastName, $result['last_name']);
    }
}
