<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Class ProductContext
 */
class ProductContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var DesignerContext
     */
    private $designerContext;

    /**
     * @var string
     */
    private $productId;

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->apiContext = $environment->getContext('ApiContext');
        $this->designerContext = $environment->getContext('DesignerContext');
    }

    /**
     * @Given I create product witch sku :sku
     *
     * @param string $sku
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iCreateProduct(string $sku): void
    {
        $templateId = $this->designerContext->getTemplateId();

        $sku .= time();

        $this->apiContext->post(
            '/api/v1/EN/products',
            ['sku' => $sku, 'templateId' => $templateId],
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I get product Id
     */
    public function iGetProductId(): void
    {
        $this->productId = $this->apiContext->getContent()['id'];
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
    }
}
