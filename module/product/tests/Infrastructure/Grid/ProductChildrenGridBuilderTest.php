<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid;

use Ergonode\Product\Infrastructure\Grid\ProductChildrenGridBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ProductChildrenGridBuilderTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $builder = new ProductChildrenGridBuilder();
        $grid = $builder->build($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
