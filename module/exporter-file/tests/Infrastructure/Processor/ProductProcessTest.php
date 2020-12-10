<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use PHPUnit\Framework\TestCase;

class ProductProcessTest extends TestCase
{
    public function testProcess(): void
    {
        $attributeQuery = $this->createMock(AttributeQueryInterface::class);
        $attributeQuery->expects(self::once())->method('getDictionary');
        $calculator = $this->createMock(TranslationInheritanceCalculator::class);
        $attributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $templateRepository = $this->createMock(TemplateRepositoryInterface::class);
        $templateRepository->expects(self::once())->method('load')
            ->willReturn($this->createMock(Template::class));

        $channel = $this->createMock(FileExportChannel::class);
        $product = $this->createMock(AbstractProduct::class);

        $processor = new ProductProcessor($attributeQuery, $calculator, $attributeRepository, $templateRepository);
        $processor->process($channel, $product);
    }
}
