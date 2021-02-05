<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\AttributeProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeProcessorTest extends TestCase
{
    /**
     * @var AbstractAttribute|MockObject
     */
    private AbstractAttribute $attribute;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->attribute = $this->createMock(AbstractAttribute::class);
        $this->channel = $this->createMock(FileExportChannel::class);
    }

    public function testProcessor(): void
    {
        $language = $this->createMock(Language::class);
        $language->method('getCode')->willReturn('en_GB');
        $this->channel->method('getLanguages')->willReturn([$language]);

        $attributeCode = $this->createMock(AttributeCode::class);
        $attributeCode->method('getValue')->willReturn('test_attribute_code');
        $this->attribute->method('getCode')->willReturn($attributeCode);

        $this->attribute->method('getType')->willReturn('any_type');

        $scope = $this->createMock(AttributeScope::class);
        $scope->method('getValue')->willReturn(AttributeScope::LOCAL);
        $this->attribute->method('getScope')->willReturn($scope);

        $processor = new AttributeProcessor();
        $result = $processor->process($this->channel, $this->attribute);

        $languageData = $result->getLanguages()['en_GB'];

        self::assertArrayHasKey('_code', $languageData->getValues());
        self::assertArrayHasKey('_type', $languageData->getValues());
        self::assertArrayHasKey('_language', $languageData->getValues());
        self::assertArrayHasKey('_name', $languageData->getValues());
        self::assertArrayHasKey('_hint', $languageData->getValues());
        self::assertArrayHasKey('_placeholder', $languageData->getValues());
        self::assertArrayHasKey('_scope', $languageData->getValues());
        self::assertArrayHasKey('_parameters', $languageData->getValues());

        self::assertEquals('test_attribute_code', $languageData->getValues()['_code']);
        self::assertEquals('any_type', $languageData->getValues()['_type']);
        self::assertEquals('en_GB', $languageData->getValues()['_language']);
        self::assertEquals(AttributeScope::LOCAL, $languageData->getValues()['_scope']);
    }

    public function testProcessorNoLanguage(): void
    {
        $processor = new AttributeProcessor();
        $result = $processor->process($this->channel, $this->attribute);

        self::assertEmpty($result->getLanguages());
    }
}
