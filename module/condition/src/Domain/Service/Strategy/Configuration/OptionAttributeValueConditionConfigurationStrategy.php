<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy\Configuration;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class OptionAttributeValueConditionConfigurationStrategy implements ConfigurationStrategyInterface
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
        return OptionAttributeValueCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->query->getDictionary([SelectAttribute::TYPE, MultiSelectAttribute::TYPE]);

        return [
            'type' => OptionAttributeValueCondition::TYPE,
            'name' => $this->translator->trans(OptionAttributeValueCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this->translator->trans(OptionAttributeValueCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'attribute',
                    'type' => 'SELECT',
                    'options' => $codes,
                ],
                [
                    'name' => 'value',
                    'type' => 'TEXT',
                ],
            ],
        ];
    }
}
