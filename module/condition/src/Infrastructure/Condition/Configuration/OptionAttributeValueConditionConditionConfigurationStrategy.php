<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Contracts\Translation\TranslatorInterface;

class OptionAttributeValueConditionConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    private AttributeQueryInterface $attributeQuery;

    private OptionQueryInterface $optionQuery;

    private TranslatorInterface $translator;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        OptionQueryInterface $optionQuery,
        TranslatorInterface $translator
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->optionQuery = $optionQuery;
        $this->translator = $translator;
    }


    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return OptionAttributeValueCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->attributeQuery->getDictionary([SelectAttribute::TYPE, MultiSelectAttribute::TYPE]);
        asort($codes);
        $complexOptions = [];
        foreach (array_keys($codes) as $attributeId) {
            $options = $this->optionQuery->getList(new AttributeId($attributeId), $language);
            if ($options) {
                $complexOptions[$attributeId] = $options;
            }
        }

        return [
            'type' => OptionAttributeValueCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(OptionAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(OptionAttributeValueCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'attribute',
                    'type' => 'SELECT',
                    'options' => $codes,
                ],
                [
                    'name' => 'value',
                    'type' => 'SELECT',
                    'affectedBy' => 'attribute',
                    'complexOptions' => $complexOptions,
                ],
            ],
        ];
    }
}
