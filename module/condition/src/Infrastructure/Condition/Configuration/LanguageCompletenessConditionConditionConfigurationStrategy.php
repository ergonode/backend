<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Condition\Domain\Condition\LanguageCompletenessCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Provider\LanguageProviderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguageCompletenessConditionConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var LanguageProviderInterface
     */
    private LanguageProviderInterface $languageProvider;

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
        return LanguageCompletenessCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(Language $language): array
    {
        return [
            'type' => LanguageCompletenessCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(LanguageCompletenessCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(LanguageCompletenessCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'completeness',
                    'type' => 'SELECT',
                    'options' => [
                        LanguageCompletenessCondition::COMPLETE => $this
                            ->translator
                            ->trans(
                                LanguageCompletenessCondition::PRODUCT_TRANSLATION_COMPLETE,
                                [],
                                'condition',
                                $language->getCode()
                            ),
                        LanguageCompletenessCondition::NOT_COMPLETE => $this
                            ->translator
                            ->trans(
                                LanguageCompletenessCondition::PRODUCT_TRANSLATION_NOT_COMPLETE,
                                [],
                                'condition',
                                $language->getCode()
                            ),
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
