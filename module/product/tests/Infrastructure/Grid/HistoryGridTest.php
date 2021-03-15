<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\ProductHistoryGridBuilder;
use PHPUnit\Framework\TestCase;

class HistoryGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        $builder = new ProductHistoryGridBuilder();
        $grid = $builder->build($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
