<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductHasStatusConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var StatusQueryInterface
     */
    private StatusQueryInterface $statusQuery;

    /**
     * @param TranslatorInterface  $translator
     * @param StatusQueryInterface $statusQuery
     */
    public function __construct(TranslatorInterface $translator, StatusQueryInterface $statusQuery)
    {
        $this->translator = $translator;
        $this->statusQuery = $statusQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductHasStatusCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $statuses = $this->statusQuery->getDictionary($language);
        asort($statuses);

        return [
            'type' => ProductHasStatusCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(ProductHasStatusCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(ProductHasStatusCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'operator',
                    'type' => 'SELECT',
                    'options' => [
                        ProductHasStatusCondition::HAS =>
                            $this->translator->trans(
                                ProductHasStatusCondition::HAS,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        ProductHasStatusCondition::NOT_HAS =>
                            $this->translator->trans(
                                ProductHasStatusCondition::NOT_HAS,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                    ],
                ],
                [
                    'name' => 'value',
                    'type' => 'MULTI_SELECT',
                    'options' => $statuses,
                ],
            ],
        ];
    }
}
