<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AttributeTypeDictionaryProviderTest extends TestCase
{
    /**
     */
    public function testProvidingAttributeTypeDictionary(): void
    {
        /** @var TranslatorInterface | MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->at(0))->method('trans')->willReturn('text_translation');
        $translator->expects($this->at(1))->method('trans')->willReturn('textarea_translation');
        $translator->expects($this->at(2))->method('trans')->willReturn('numeric_translation');
        $translator->expects($this->at(3))->method('trans')->willReturn('select_translation');
        $translator->expects($this->at(4))->method('trans')->willReturn('multiselect_translation');
        $translator->expects($this->at(5))->method('trans')->willReturn('price_translation');
        $translator->expects($this->at(6))->method('trans')->willReturn('date_translation');
        $translator->expects($this->at(7))->method('trans')->willReturn('unit_translation');
        $translator->expects($this->at(8))->method('trans')->willReturn('image_translation');

        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);

        $provider = new AttributeTypeDictionaryProvider($translator);
        $checkTranslations = [
            'TEXT' => 'text_translation',
            'TEXTAREA' => 'textarea_translation',
            'NUMERIC' => 'numeric_translation',
            'SELECT' => 'select_translation',
            'MULTI_SELECT' => 'multiselect_translation',
            'PRICE' => 'price_translation',
            'DATE' => 'date_translation',
            'UNIT' => 'unit_translation',
            'IMAGE' => 'image_translation',
        ];
        $checkTypes = [
            'TEXT',
            'TEXTAREA',
            'NUMERIC',
            'SELECT',
            'MULTI_SELECT',
            'PRICE',
            'DATE',
            'UNIT',
            'IMAGE',
        ];

        $this->assertSame($checkTranslations, $provider->getDictionary($language));
        $this->assertSame($checkTypes, $provider->getTypes());
    }
}
