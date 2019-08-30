<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class DesignerContext
 */
class DesignerContext implements Context
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
     * @var array
     */
    private $template;

    /**
     * @var string
     */
    private $templateId;

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
    }

    /**
     * @Given I fill template correctly
     */
    public function iFillTextAttribute(): void
    {
        $this->template = [
            'name' => 'Template name',
            'elements' => [],
        ];
    }

    /**
     * @Given I add attribute to template
     */
    public function iAddAttributeToTemplate(): void
    {
        $this->template['elements'][] = [
            'position' => [
                'x' => 0,
                'y' => 1,
            ],
            'size' => [
                'width' => 2,
                'height' => 1,
            ],
            'variant' => 'attribute',
            'type' => 'text',
            'properties' => [
                'required' => true,
                'attribute_id' => $this->attributeContext->getAttributeId(),
            ],
        ];
    }

    /**
     * @When I create template
     *
     * @throws GuzzleException
     */
    public function iCreateTemplate(): void
    {
        $this->apiContext->post(
            '/api/v1/EN/templates',
            $this->template,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I update template
     *
     * @throws GuzzleException
     */
    public function iUpdateTemplate(): void
    {
        $this->apiContext->put(
            '/api/v1/EN/templates/' . $this->templateId,
            $this->template,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I delete template
     *
     * @throws GuzzleException
     */
    public function iDeleteTemplate(): void
    {
        $this->apiContext->delete(
            '/api/v1/EN/templates/' . $this->templateId,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I get template
     *
     * @throws GuzzleException
     */
    public function iGetTemplate(): void
    {
        $this->apiContext->get(
            '/api/v1/EN/templates/' . $this->templateId,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I get template Id
     */
    public function iGetTemplateId(): void
    {
        $this->templateId = $this->apiContext->getContent()['id'];
    }

    /**
     * @When I get designer templates
     *
     * @throws GuzzleException
     */
    public function iGetDesignerTemplates(): void
    {
        $this->apiContext->get(
            '/api/v1/EN/templates',
            $this->apiContext->getToken()
        );
    }

    /**
     * @return string
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }
}
