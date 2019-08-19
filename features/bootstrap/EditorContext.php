<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Class EditorContext
 */
class EditorContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var AttributeContext
     */
    private $attributeContext;

    /**
     * @var ProductContext
     */
    private $productContext;

    /**
     * @var string
     */
    private $draftId;

    /**
     * @var string
     */
    private $language;

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();

        $this->apiContext = $environment->getContext('ApiContext');
        $this->attributeContext = $environment->getContext('AttributeContext');
        $this->productContext = $environment->getContext('ProductContext');
    }

    /**
     * @Given I create product draft
     */
    public function CreateProductDraft(): void
    {
        $productId = $this->productContext->getProductId();

        $this->apiContext->post(
            '/api/v1/EN/products/drafts',
            ['productId' => $productId],
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I get draft Id
     */
    public function iGetDraftId(): void
    {
        $this->draftId = $this->apiContext->getContent()['id'];
    }

    /**
     * @Given I switch to language :language
     *
     * @param string $language
     */
    public function iSwitchToLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @Given I set attribute :value
     *
     * @param string $value
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iChangeAttributeValue(string $value): void
    {
        $this->apiContext->put(
            sprintf('/api/v1/%s/products/%s/draft/%s/value', $this->language, $this->productContext->getProductId(), $this->attributeContext->getAttributeId()),
            ['value' => $value],
            $this->apiContext->getToken()
        );
    }

    /**
     * @Given I get Draft View
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iGetDraftView(): void
    {
        $productId = $this->productContext->getProductId();

        $this->apiContext->get(
            sprintf('/api/v1/%s/products/%s/template', $this->language, $productId),
            $this->apiContext->getToken()
        );
    }

    /**
     * @Given I set sku :sku
     *
     * @param string $sku
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function iSetSku(string $sku): void
    {
        $this->apiContext->put(
            sprintf('/api/editor/product/draft/%s/sku', $this->draftId),
            ['sku' => $sku],
            $this->apiContext->getToken()
        );
    }


    /**
     * @Given I apply draft
     */
    public function iPersistDraft(): void
    {
        $this->apiContext->put(
            sprintf('/api/v1/%s/products/%s/draft/persist', $this->language, $this->productContext->getProductId()),
            [],
            $this->apiContext->getToken()
        );
    }
}
