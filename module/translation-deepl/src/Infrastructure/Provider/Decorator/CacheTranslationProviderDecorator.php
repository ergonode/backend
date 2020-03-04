<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Cache\DatabaseTranslationCache;
use Ergonode\TranslationDeepl\Infrastructure\Provider\DeeplTranslationProvider;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationProviderInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CacheTranslationProviderDecorator implements TranslationProviderInterface
{
    public const NAMESPACE = 'a16c8554-70f5-487e-b0b7-a4a52e890ab3';
    /**
     * @var DeeplTranslationProvider
     */
    private DeeplTranslationProvider $provider;

    /**
     * @var DatabaseTranslationCache
     */
    private DatabaseTranslationCache $cache;

    /**
     * CacheTranslationProviderDecorator constructor.
     *
     * @param TranslationProviderInterface $provider
     * @param DatabaseTranslationCache     $cache
     */
    public function __construct(TranslationProviderInterface $provider, DatabaseTranslationCache $cache)
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
        $name = sprintf('%s_%s_%s', $sourceLanguage, $targetLanguage, $content);
        $translationDeeplUuid = Uuid::uuid5(self::NAMESPACE, $name);
        $translation = $this->cache->get($translationDeeplUuid);

        if (!$translation) {
            $translation = $this->provider->provide($content, $sourceLanguage, $targetLanguage);
            $this->cache->set($translationDeeplUuid, $translation);
        }

        return $translation;
    }
}
