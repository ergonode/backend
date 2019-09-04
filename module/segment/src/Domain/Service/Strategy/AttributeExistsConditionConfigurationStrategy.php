<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Service\Strategy;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Service\SegmentConfigurationStrategyInterface;
use Ergonode\Segment\Domain\Condition\AttributeExistsCondition;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AttributeExistsConditionConfigurationStrategy implements SegmentConfigurationStrategyInterface
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
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return AttributeExistsCondition::TYPE === $type;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->query->getAllAttributeCodes();

        return [
            'type' => AttributeExistsCondition::TYPE,
            'name' => $this->translator->trans(AttributeExistsCondition::TYPE, [], 'segment', $language->getCode()),
            'phrase' => $this->translator->trans(AttributeExistsCondition::PHRASE, [], 'segment', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'attribute',
                    'type' => 'SELECT',
                    'options' => array_combine($codes, $codes),
                ],
            ],
        ];
    }
}
