<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Infrastructure\Provider\LanguageProviderInterface;
use Ergonode\Condition\Domain\Condition\AttributeTranslationExistsCondition;

class AttributeTranslationExistsConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
{
    private AttributeQueryInterface $query;

    private LanguageProviderInterface $languageProvider;

    private TranslatorInterface $translator;

    public function __construct(
        AttributeQueryInterface $query,
        LanguageProviderInterface $languageProvider,
        TranslatorInterface $translator
    ) {
        $this->query = $query;
        $this->languageProvider = $languageProvider;
        $this->translator = $translator;
    }

    public function supports(string $type): bool
    {
        return AttributeTranslationExistsCondition::TYPE === $type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->query->getDictionary();
        asort($codes);

        return [
            'type' => AttributeTranslationExistsCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(AttributeTranslationExistsCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(AttributeTranslationExistsCondition::PHRASE, [], 'condition', $language->getCode()),
            'parameters' => [
                [
                    'name' => 'attribute',
                    'type' => 'SELECT',
                    'options' => $codes,
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
