<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\DataStructure\LanguageData;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductProcessTest extends TestCase
{
    public function testProcess(): void
    {
        $attributeQuery = $this->createMock(AttributeQueryInterface::class);
        $attributeQuery->expects(self::once())->method('getDictionary')
            ->willReturn([
                (string) Uuid::uuid4() => 'custom',
            ]);
        $calculator = $this->createMock(TranslationInheritanceCalculator::class);
        $attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $templateRepository = $this->createMock(TemplateRepositoryInterface::class);
        $optionQuery = $this->createMock(OptionQueryInterface::class);
        $categoryQuery = $this->createMock(CategoryQueryInterface::class);
        $templateRepository
            ->expects(self::once())
            ->method('load')
            ->willReturn($this->createMock(Template::class));

        $channel = $this->createMock(FileExportChannel::class);
        $product = $this->createMock(AbstractProduct::class);

        $channel
            ->expects($this->once())
            ->method('getLanguages')
            ->willReturn([new Language('pl_PL')]);
        $product
            ->expects($this->once())
            ->method('hasAttribute')
            ->willReturn(true);
        $attribute = $this->createMock(AbstractOptionAttribute::class);
        $attributeRepository
            ->expects($this->once())
            ->method('load')->willReturn($attribute);
        $calculator->method('calculate')->willReturn([(string) Uuid::uuid4()]);
        $optionKey = new OptionKey('option_key');
        $optionQuery->expects($this->once())->method('findKey')->willReturn($optionKey);

        $processor = new ProductProcessor(
            $attributeQuery,
            $calculator,
            $attributeRepository,
            $templateRepository,
            $optionQuery,
            $categoryQuery
        );

        $data = $processor->process($channel, $product);

        $languageData = new LanguageData();
        $languageData->set('_sku', '');
        $languageData->set('_type', '');
        $languageData->set('_language', 'pl_PL');
        $languageData->set('_template', '');
        $languageData->set('_categories', '');
        $languageData->set('custom', 'option_key');

        $this->assertEquals(
            [
                'pl_PL' => $languageData,
            ],
            $data->getLanguages(),
        );
    }
}
