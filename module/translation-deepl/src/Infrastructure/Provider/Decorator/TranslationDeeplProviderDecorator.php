<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Cache\DatabaseTranslationCache;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProvider;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProviderInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class TranslationDeeplProviderDecorator implements TranslationDeeplProviderInterface
{

    /**
     * @var TranslationDeeplProvider
     */
    private $provider;

    /**
     * @var DatabaseTranslationCache
     */
    private $cache;

    /**
     * TranslationDeeplProviderDecorator constructor.
     *
     * @param TranslationDeeplProvider $provider
     * @param DatabaseTranslationCache $cache
     */
    public function __construct(TranslationDeeplProvider $provider, DatabaseTranslationCache $cache)
    {
        $this->provider = $provider;
        $this->cache = $cache;
    }

    /**
     * @param string   $content
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     *
     * @return string
     */
    public function provide(string $content, Language $sourceLanguage, Language $targetLanguage): string
    {
        $namespace = 'a16c8554-70f5-487e-b0b7-a4a52e890ab3';
        $name = sprintf('%s_%s_%s', $sourceLanguage, $targetLanguage, $content);
        $translationDeeplUuid = Uuid::uuid5($namespace, $name);
        $translation = $this->cache->get($translationDeeplUuid);

        if (!$translation) {
            $translation = $this->provider->provide($content, $sourceLanguage, $targetLanguage);
            $this->cache->set($translationDeeplUuid, $translation);
        }

        return $translation;
    }
}
