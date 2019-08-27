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
class CategoryTreeContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var string
     */
    private $categoryTreeId;

    /**
     * @var array
     */
    private $categoryTree;

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
     * @Given I fill category tree witch code :code and :name
     *
     * @param string $code
     * @param string $name
     */
    public function iFillCategory(string $code, string $name): void
    {
        $this->categoryTree = [
            'code' => $code.time(),
            'name' => $name,
        ];
    }

    /**
     * @Given I create category tree
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iCreateTreeCategory(): void
    {
        $this->apiContext->post(
            '/api/v1/EN/trees',
            $this->categoryTree,
            $this->apiContext->getToken()
        );
    }

    /**
     * @Given I get category tree :id
     * @Given I get added category tree
     *
     * @param string|null $id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iGetCategoryTree(string $id = null): void
    {
        $id = $id?:$this->getCategoryTreeId();

        $this->apiContext->get(
            sprintf('/api/v1/%s/trees/%s', $this->apiContext->getLanguage(), $id),
            $this->apiContext->getToken()
        );
        $this->categoryTree = $this->apiContext->getContent();
    }

    /**
     * @Then I remember category tree id
     */
    public function iRememberCategoryTreeId(): void
    {
        $this->categoryTreeId = $this->apiContext->getContent()['id'];
    }

    /**
     * @return string
     */
    public function getCategoryTreeId(): string
    {
        return $this->categoryTreeId;
    }
}
