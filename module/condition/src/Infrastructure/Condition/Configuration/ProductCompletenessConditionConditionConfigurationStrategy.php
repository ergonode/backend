<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductCompletenessConditionConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;


    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductCompletenessCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        return [
            'type' => ProductCompletenessCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(ProductCompletenessCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(ProductCompletenessCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'completeness',
                    'type' => 'SELECT',
                    'options' => [
                        ProductCompletenessCondition::COMPLETE => $this
                            ->translator
                            ->trans(
                                ProductCompletenessCondition::PRODUCT_COMPLETE,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        ProductCompletenessCondition::NOT_COMPLETE => $this
                            ->translator
                            ->trans(
                                ProductCompletenessCondition::PRODUCT_NOT_COMPLETE,
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
