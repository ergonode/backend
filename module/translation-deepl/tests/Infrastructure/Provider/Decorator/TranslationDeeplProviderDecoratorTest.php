<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Tests\Infrastructure\Provider\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Cache\DatabaseTranslationCache;
use Ergonode\TranslationDeepl\Infrastructure\Provider\Decorator\TranslationDeeplProviderDecorator;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProvider;
use PHPUnit\Framework\TestCase;

/**
 */
class TranslationDeeplProviderDecoratorTest extends TestCase
{
    /**
     */
    public function testProvide(): void
    {
        $provider = $this->createMock(TranslationDeeplProvider::class);
        $provider->method('provide')->willReturn('ABCD');

        $cache = $this->createMock(DatabaseTranslationCache::class);
        $cache
            ->method('get')
            ->willReturn(null);
        $cache
            ->expects($this->once())
            ->method('set');

        $sourceLanguage = $this->createMock(Language::class);
        $targetLanguage = $this->createMock(Language::class);

        $decorator = new TranslationDeeplProviderDecorator($provider, $cache);
        $this->assertEquals('ABCD', $decorator->provide('DEFG', $sourceLanguage, $targetLanguage));
    }

    /**
     */
    public function testCache(): void
    {
        $provider = $this->createMock(TranslationDeeplProvider::class);
        $provider
            ->expects($this->never())
            ->method('provide');

        $cache = $this->createMock(DatabaseTranslationCache::class);
        $cache
            ->method('get')
            ->willReturn('ABC');
        $cache
            ->expects($this->never())
            ->method('set');

        $sourceLanguage = $this->createMock(Language::class);
        $targetLanguage = $this->createMock(Language::class);

        $decorator = new TranslationDeeplProviderDecorator($provider, $cache);
        $this->assertEquals('ABC', $decorator->provide('DEFG', $sourceLanguage, $targetLanguage));
    }
}
