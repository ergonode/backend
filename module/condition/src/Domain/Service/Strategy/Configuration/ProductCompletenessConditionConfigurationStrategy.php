<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy\Configuration;

use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Condition\Domain\Service\ConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ProductCompletenessConditionConfigurationStrategy implements ConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LanguageProviderInterface
     */
    private $languageProvider;

    /**
     * @param TranslatorInterface       $translator
     * @param LanguageProviderInterface $languageProvider
     */
    public function __construct(
        TranslatorInterface $translator,
        LanguageProviderInterface $languageProvider
    ) {
        $this->translator = $translator;
        $this->languageProvider = $languageProvider;
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
            'name' => $this->translator->trans(ProductCompletenessCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this->translator->trans(ProductCompletenessCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'completeness',
                    'type' => 'SELECT',
                    'options' => [
                        ProductCompletenessCondition::COMPLETE => $this->translator->trans('Product is complete', [], 'condition', $language->getCode()),
                        ProductCompletenessCondition::NOT_COMPLETE => $this->translator->trans('Product is not complete', [], 'condition', $language->getCode()),
                    ],
                ],
                [
                    'name' => 'language',
                    'type' => 'SELECT',
                    'options' => $this->languageProvider->getActiveLanguages($language),
                ],
            ],
        ];
    }
}
