<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductHasStatusConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    private TranslatorInterface $translator;

    private StatusQueryInterface $statusQuery;

    private LanguageQueryInterface $languageQuery;

    public function __construct(
        TranslatorInterface $translator,
        StatusQueryInterface $statusQuery,
        LanguageQueryInterface $languageQuery
    ) {
        $this->translator = $translator;
        $this->statusQuery = $statusQuery;
        $this->languageQuery = $languageQuery;
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

        $languages = $this->languageQuery->getDictionaryActive();
        asort($languages);

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
                [
                    'name' => 'language',
                    'type' => 'MULTI_SELECT',
                    'options' => $languages,
                ],
            ],
        ];
    }
}
