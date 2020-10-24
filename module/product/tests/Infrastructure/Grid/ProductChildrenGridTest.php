<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid;

use Ergonode\Product\Infrastructure\Grid\ProductChildrenGrid;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ProductChildrenGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $grid = new ProductChildrenGrid();
        $grid->init($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
