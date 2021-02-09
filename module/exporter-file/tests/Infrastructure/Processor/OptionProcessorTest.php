<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\OptionProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OptionProcessorTest extends TestCase
{
    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private AttributeRepositoryInterface $attributeRepository;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->channel = $this->createMock(FileExportChannel::class);
    }

    public function testProcessor(): void
    {
        $attributeCode = $this->createMock(AttributeCode::class);
        $attributeCode->method('getValue')->willReturn('test_attribute_code');

        $attribute = $this->createMock(AbstractAttribute::class);
        $attribute->method('getCode')->willReturn($attributeCode);
        $this->attributeRepository->method('load')->willReturn($attribute);

        $language = $this->createMock(Language::class);
        $language->method('getCode')->willReturn('en_GB');
        $this->channel->method('getLanguages')->willReturn([$language]);

        $optionCode = $this->createMock(OptionKey::class);
        $optionCode->method('getValue')->willReturn('test_option_key');

        $option = $this->createMock(AbstractOption::class);
        $option->method('getCode')->willReturn($optionCode);

        $processor = new OptionProcessor($this->attributeRepository);
        $result = $processor->process($this->channel, $option);

        $languageData = $result->getLanguages()['en_GB'];

        self::assertArrayHasKey('_code', $languageData->getValues());
        self::assertArrayHasKey('_attribute_code', $languageData->getValues());
        self::assertArrayHasKey('_language', $languageData->getValues());
        self::assertArrayHasKey('_label', $languageData->getValues());

        self::assertEquals('test_option_key', $languageData->getValues()['_code']);
        self::assertEquals('test_attribute_code', $languageData->getValues()['_attribute_code']);
        self::assertEquals('en_GB', $languageData->getValues()['_language']);
    }

    public function testEmptyDataProcessor(): void
    {
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->attributeRepository->method('load')->willReturn($attribute);

        $option = $this->createMock(AbstractOption::class);


        $processor = new OptionProcessor($this->attributeRepository);
        $result = $processor->process($this->channel, $option);

        self::assertEmpty($result->getLanguages());
    }

    public function testInvalidArgumentExceptionProcessor(): void
    {
        $this->expectException(ExportException::class);

        $option = $this->createMock(AbstractOption::class);

        $processor = new OptionProcessor($this->attributeRepository);
        $processor->process($this->channel, $option);
    }
}
