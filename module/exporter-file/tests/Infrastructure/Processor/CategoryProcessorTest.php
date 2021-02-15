<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\CategoryProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryProcessorTest extends TestCase
{
    /**
     * @var AbstractCategory|MockObject
     */
    private AbstractCategory $category;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->category = $this->createMock(AbstractCategory::class);
        $this->channel = $this->createMock(FileExportChannel::class);
    }

    public function testProcessor(): void
    {
        $language = $this->createMock(Language::class);
        $language->method('getCode')->willReturn('en_GB');
        $this->channel->method('getLanguages')->willReturn([$language]);

        $categoryCode = $this->createMock(CategoryCode::class);
        $categoryCode->method('getValue')->willReturn('test_category_code');
        $this->category->method('getCode')->willReturn($categoryCode);


        $processor = new CategoryProcessor();
        $result = $processor->process($this->channel, $this->category);

        $languageData = $result->getLanguages()['en_GB'];

        self::assertArrayHasKey('_code', $languageData->getValues());
        self::assertArrayHasKey('_language', $languageData->getValues());
        self::assertArrayHasKey('_name', $languageData->getValues());

        self::assertEquals('test_category_code', $languageData->getValues()['_code']);
        self::assertEquals('en_GB', $languageData->getValues()['_language']);
    }
}
