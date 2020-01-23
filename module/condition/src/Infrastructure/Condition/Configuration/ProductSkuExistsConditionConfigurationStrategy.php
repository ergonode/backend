<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);


namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductSkuExistsConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
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
     * @inheritDoc
     */
    public function supports(string $type): bool
    {
        return ProductSkuExistsCondition::TYPE === $type;
    }

    /**
     * @inheritDoc
     */
    public function getConfiguration(Language $language): array
    {
        return [
            'type' => ProductSkuExistsCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(ProductSkuExistsCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(ProductSkuExistsCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'operator',
                    'type' => 'SELECT',
                    'options' => [
                        '=' => $this->translator->trans('Is equal', [], 'condition', $language->getCode()),
                        '<>' => $this->translator->trans('Is not equal', [], 'condition', $language->getCode()),
                        '~' => $this->translator->trans('Has', [], 'condition', $language->getCode()),
                        'REGEXP' => $this->translator->trans('Regexp', [], 'condition', $language->getCode()),
                    ],
                ],
                [
                    'name' => 'value',
                    'type' => 'TEXT',
                ],
            ],
        ];
    }
}
