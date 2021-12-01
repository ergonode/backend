<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Configuration;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionConfigurationStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Condition\Domain\Condition\LanguageAttributeExistsCondition;
use Ergonode\Core\Infrastructure\Provider\LanguageProviderInterface;

class LanguageAttributeExistsConditionConditionConfigurationStrategy implements ConditionConfigurationStrategyInterface
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
        return LanguageAttributeExistsCondition::TYPE === $type;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(Language $language): array
    {
        $codes = $this->query->getDictionary();
        asort($codes);

        return [
            'type' => LanguageAttributeExistsCondition::TYPE,
            'name' => $this
                ->translator
                ->trans(LanguageAttributeExistsCondition::TYPE, [], 'condition', $language->getCode()),
            'phrase' => $this
                ->translator
                ->trans(LanguageAttributeExistsCondition::PHRASE, [], 'condition', $language->getCode()),
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
