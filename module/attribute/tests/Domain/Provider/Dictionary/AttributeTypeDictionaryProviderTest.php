<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeDictionaryProviderTest extends TestCase
{
    public function testProvidingAttributeTypeDictionary(): void
    {
        /** @var TranslatorInterface | MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->at(0))->method('trans')->willReturn('translation');

        $provider = $this->createMock(AttributeTypeProvider::class);
        $provider->method('provide')->willReturn(['TYPE']);


        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);

        $provider = new AttributeTypeDictionaryProvider($provider, $translator);

        $this->assertSame(['TYPE' => 'translation'], $provider->getDictionary($language));
    }
}
