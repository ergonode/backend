<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Scn\DeeplApiConnector\DeeplClient;
use Scn\DeeplApiConnector\Model\Translation;
use Scn\DeeplApiConnector\Model\TranslationConfig;

/**
 */
class DeeplTranslationProvider implements TranslationProviderInterface
{
    /**
     * @var string
     */
    private string $deeplAuthKey;

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
        $translation = new TranslationConfig(
            $content,
            $targetLanguage->getCode(),
            $sourceLanguage->getCode()
        );

        $deepl = DeeplClient::create($this->deeplAuthKey);
        /** @var Translation $response */
        $response = $deepl->getTranslation($translation);

        return $response->getText();
    }
}
