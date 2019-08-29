<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 */
class AttributeContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

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

        $this->apiContext = $environment->getContext('ApiContext');
        $this->storageContext = $environment->getContext('StorageContext');
    }

    /**
     * @Then remember first attribute group as "attribute_group"
     */
    public function rememberFirstAttributeGroup(): void
    {
        $response = $this->apiContext->getLastResponseBody();
        $this->storageContext->add('attribute_group', $response[count($response)-1]->id);
    }
}
