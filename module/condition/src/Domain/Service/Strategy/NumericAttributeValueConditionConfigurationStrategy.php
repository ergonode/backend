<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class NumericAttributeValueConditionConfigurationStrategy implements ConfigurationStrategyInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private $query;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param AttributeQueryInterface $query
     * @param TranslatorInterface     $translator
     */
    public function __construct(AttributeQueryInterface $query, TranslatorInterface $translator)
    {
        $this->query = $query;
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupportedBy(string $type): bool
    {
        return TextAttributeValueCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->query->getAttributeCodes([NumericAttribute::TYPE]);

        return [
            'type' => TextAttributeValueCondition::TYPE,
            'name' => $this->translator->trans(TextAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this->translator->trans(TextAttributeValueCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'attribute',
                    'type' => 'SELECT',
                    'options' => array_combine($codes, $codes),
                ],
                [
                    'name' => 'operator',
                    'type' => 'select',
                    'options' => [
                        '=' =>'=',
                        '<>' => '<>',
                        '>' => '>',
                        '<' => '<',
                        '>=' => '>=',
                        '<=' => '<=',
                    ],
                ],
                [
                    'name' => 'value',
                    'type' => 'TEXT',
                ]
            ],
        ];
    }
}
