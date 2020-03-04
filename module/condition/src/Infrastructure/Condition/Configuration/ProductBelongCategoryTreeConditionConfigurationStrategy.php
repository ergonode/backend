<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductBelongCategoryTreeConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var TreeQueryInterface
     */
    private TreeQueryInterface $query;

    /**
     * @param TranslatorInterface $translator
     * @param TreeQueryInterface  $query
     */
    public function __construct(TranslatorInterface $translator, TreeQueryInterface $query)
    {
        $this->translator = $translator;
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductBelongCategoryTreeCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        $tree = $this->query->getDictionary($language);
        asort($tree);

        return [
            'type' => ProductBelongCategoryTreeCondition::TYPE,
            'name' => $this->translator->trans(
                ProductBelongCategoryTreeCondition::TYPE,
                [],
                'condition',
                $language->getCode()
            ),
            'phrase' => $this->translator->trans(
                ProductBelongCategoryTreeCondition::PHRASE,
                [],
                'condition',
                $language->getCode()
            ),
            'parameters' => [
                [
                    'name' => 'tree',
                    'type' => 'SELECT',
                    'options' => $tree,
                ],
                [
                    'name' => 'operator',
                    'type' => 'SELECT',
                    'options' => [
                        ProductBelongCategoryTreeCondition::BELONG_TO => $this
                            ->translator
                            ->trans(
                                ProductBelongCategoryTreeCondition::BELONG_TO,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        ProductBelongCategoryTreeCondition::NOT_BELONG_TO => $this
                            ->translator
                            ->trans(
                                ProductBelongCategoryTreeCondition::NOT_BELONG_TO,
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
