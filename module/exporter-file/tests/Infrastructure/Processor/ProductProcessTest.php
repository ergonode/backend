<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

class ProductProcessTest extends TestCase
{
    public function testProcess(): void
    {
        $attributeQuery = $this->createMock(AttributeQueryInterface::class);
        $attributeQuery->expects(self::once())->method('getDictionary');
        $calculator = $this->createMock(TranslationInheritanceCalculator::class);
        $repository = $this->createMock(AttributeRepositoryInterface::class);

        $channel = $this->createMock(FileExportChannel::class);
        $product = $this->createMock(AbstractProduct::class);

        $processor = new ProductProcessor($attributeQuery, $calculator, $repository);
        $processor->process($channel, $product);
    }
}
