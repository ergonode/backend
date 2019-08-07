<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Scn\DeeplApiConnector\DeeplClient;
use Scn\DeeplApiConnector\Enum\LanguageEnum;
use Scn\DeeplApiConnector\Model\TranslationConfig;

/**
 */
class TranslationDeeplProvider implements TranslationDeeplProviderInterface
{
    /**
     * @var string
     */
    private $deeplAuthKey;

    /**
     * @param string $deeplAuthKey
     */
    public function __construct(string $deeplAuthKey)
    {
        $this->deeplAuthKey = $deeplAuthKey;
    }

    /**
     * {@inheritDoc}
     */
    public function provide(string $content, Language $sourceLanguage, Language $targetLanguage): string
    {
        $this->validate($sourceLanguage, $targetLanguage);

        $translation = new TranslationConfig(
            $content,
            $targetLanguage->getCode(),
            $sourceLanguage->getCode()
        );

        $deepl = DeeplClient::create($this->deeplAuthKey);
        /** @var \Scn\DeeplApiConnector\Model\Translation $response */
        $response = $deepl->getTranslation($translation);

        return $response->getText();
    }

    /**
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     *
     * @throws \ReflectionException
     */
    private function validate(Language $sourceLanguage, Language $targetLanguage): void
    {
        $languageEnumReflection = new \ReflectionClass(LanguageEnum::class);
        $availableLanguages = $languageEnumReflection->getConstants();

        if (!in_array($sourceLanguage->getCode(), $availableLanguages, true)) {
            throw new \OutOfBoundsException('Source language is not supported');
        }

        if (!in_array($targetLanguage->getCode(), $availableLanguages, true)) {
            throw new \OutOfBoundsException('Target language is not supported');
        }
    }
}
