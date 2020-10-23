<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Provider\Dictionary;

use Ergonode\Category\Application\Provider\CategoryTypeProvider;
use Ergonode\Category\Domain\Provider\Dictionary\CategoryTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Translation\TranslatorInterface;
use PHPUnit\Framework\TestCase;

class CategoryTypeDictionaryProviderTest extends TestCase
{
    public function testProvidingCategoryTypeDictionary(): void
    {
        /** @var TranslatorInterface | MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->at(0))->method('trans')->willReturn('translation');

        $provider = $this->createMock(CategoryTypeProvider::class);
        $provider->method('provide')->willReturn(['TYPE']);


        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);

        $provider = new CategoryTypeDictionaryProvider($provider, $translator);

        $this->assertSame(['TYPE' => 'translation'], $provider->getDictionary($language));
    }
}
