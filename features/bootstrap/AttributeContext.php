<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeContext implements Context
{
    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var array
     */
    private $attribute;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var string
     */
    private $attributeId;

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
     * @Given I fill text attribute correctly
     */
    public function iFillTextAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('TEXT_ANY_CODE_%s', $micro),
            'type' => \Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
            ],
        ];
    }

    /**
     * @Given I fill textarea attribute correctly
     */
    public function iFillTextareaAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('TEXTAREA_ANY_CODE_%s', $micro),
            'type' => \Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
            ],
        ];
    }

    /**
     * @Given I fill select attribute correctly
     */
    public function iFillSelectAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
        ];
    }

    /**
     * @Given I fill multi select attribute correctly
     */
    public function iFillMultiSelectAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
        ];
    }

    /**
     * @Given I fill image attribute correctly
     */
    public function iFillImageAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\AttributeImage\Domain\Entity\ImageAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
                'formats' => \Ergonode\AttributeImage\Domain\ValueObject\ImageFormat::AVAILABLE,
            ],
        ];
    }

    /**
     * @Given I fill date attribute correctly
     */
    public function iFillDateAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\AttributeDate\Domain\Entity\DateAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
                'format' => \Ergonode\AttributeDate\Domain\ValueObject\DateFormat::YYYY_MM_DD,
            ],
        ];
    }

    /**
     * @Given I fill price attribute correctly
     */
    public function iFillPriceAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\AttributePrice\Domain\Entity\PriceAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
                'currency' => 'PLN',
            ],
        ];
    }

    /**
     * @Given I fill unit attribute correctly
     */
    public function iFillUnitAttribute(): void
    {
        $micro = str_replace('.', '', microtime(true));

        $this->attribute = [
            'code' => \sprintf('ANY_CODE_%s', $micro),
            'type' => \Ergonode\AttributeUnit\Domain\Entity\UnitAttribute::TYPE,
            'groups' => [
                reset($this->groups)['id'],
            ],
            'parameters' => [
                'unit' => 'M',
            ],
        ];
    }

    /**
     * @When I get attribute group dictionary
     *
     * @throws GuzzleException
     */
    public function getAttributeGroupDictionary(): void
    {
        $this->apiContext->get(
            '/api/v1/EN/dictionary/attributes/groups',
            $this->apiContext->getToken()
        );

        $this->groups = $this->apiContext->getContent();

        foreach ($this->groups as $index => $group) {
            if(null === $group['id']) {
                unset($this->groups[$index]);
            }
        }

        TestCase::assertNotEmpty($this->groups, 'Attribute group dictionary shouldn\'t be empty');
    }

    /**
     * @When I create attribute
     *
     * @throws GuzzleException
     */
    public function iCreateAttribute(): void
    {
        $this->apiContext->post(
            '/api/v1/EN/attributes',
            $this->attribute,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I update attribute
     *
     * @throws GuzzleException
     */
    public function iUpdateAttribute(): void
    {
        $attribute = $this->attribute;
        unset($attribute['code']);
        $this->apiContext->put(
            '/api/v1/EN/attributes/'.$this->attributeId,
            $attribute,
            $this->apiContext->getToken()
        );
    }

    /**
     * @When I get attribute Id
     */
    public function iGetAttributeId(): void
    {
        $this->attributeId = $this->apiContext->getContent()['id'];
    }

    /**
     * @param string $field
     *
     * @When I remove value from field :field
     */
    public function iRemoveValueFromField(string $field): void
    {
        $this->setField($field, null);
    }

    /**
     * @param string $value
     * @param string $field
     *
     * @When I set :value value to field :field
     */
    public function iSetValueToField(string $value, string $field): void
    {
        $this->setField($field, $value);
    }

    /**
     * @return string
     */
    public function getAttributeId(): string
    {
        return $this->attributeId;
    }

    /**
     * @param string      $field
     * @param null|string $value
     */
    private function setField(string $field, ?string $value = null): void
    {
        if ('false' === $value) {
            $value = false;
        }

        if ('true' === $value) {
            $value = true;
        }

        $fields = explode('.', $field);

        if (2 === count($fields)) {
            $this->attribute[$fields[0]][$fields[1]] = $value;
        } elseif (3 === count($fields)) {
            $this->attribute[$fields[0]][$fields[1]][$fields[2]] = $value;
        } else {
            $this->attribute[$fields[0]] = $value;
        }
    }
}
