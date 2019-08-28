<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 */
class CategoryContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var string
     */
    private $categoryId;

    /**
     * @var array
     */
    private $category;

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
     * @Given I fill category witch code :code
     *
     * @param string $code
     */
    public function iFillCategory(string $code): void
    {
        $this->category = [
            'code' => $code.time(),
            'name' => [
            ]
        ];
    }

    /**
     * @Given I create category
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iCreateCategory(): void
    {
        $this->apiContext->post(
            '/api/v1/EN/categories',
            $this->category,
            $this->apiContext->getToken()
        );
    }

    /**
     * @Given I get category :id
     * @Given I get added category
     *
     * @param string|null $id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iGetCategory(string $id = null): void
    {
        $id = $id?:$this->getCategoryId();

        $this->apiContext->get(
            sprintf('/api/v1/%s/categories/%s', $this->apiContext->getLanguage(), $id),
            $this->apiContext->getToken()
        );
        $this->category = $this->apiContext->getContent();
    }

    /**
     * @Then I remember category id
     */
    public function iRememberCategoryId(): void
    {
        $this->categoryId = $this->apiContext->getContent()['id'];
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
}
