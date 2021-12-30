<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductBelongCategoryConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    private TranslatorInterface $translator;

    private CategoryQueryInterface $query;

    public function __construct(TranslatorInterface $translator, CategoryQueryInterface $query)
    {
        $this->translator = $translator;
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductBelongCategoryCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $categories = $this->query->getDictionary($language);
        asort($categories);

        return [
            'type' => ProductBelongCategoryCondition::TYPE,
            'name' => $this->translator->trans(
                ProductBelongCategoryCondition::TYPE,
                [],
                'condition',
                $language->getCode()
            ),
            'phrase' => $this->translator->trans(
                ProductBelongCategoryCondition::PHRASE,
                [],
                'condition',
                $language->getCode()
            ),
            'parameters' => [
                [
                    'name' => 'category',
                    'type' => 'MULTI_SELECT',
                    'options' => $categories,
                ],
                [
                    'name' => 'operator',
                    'type' => 'SELECT',
                    'options' => [
                        ProductBelongCategoryCondition::BELONG_TO => $this
                            ->translator
                            ->trans(
                                ProductBelongCategoryCondition::BELONG_TO,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        ProductBelongCategoryCondition::NOT_BELONG_TO => $this
                            ->translator
                            ->trans(
                                ProductBelongCategoryCondition::NOT_BELONG_TO,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                    ],
                ],
            ],
        ];
    }
}
