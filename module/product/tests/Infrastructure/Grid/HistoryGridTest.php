<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\HistoryGrid;
use PHPUnit\Framework\TestCase;

/**
 */
class HistoryGridTest extends TestCase
{
    /**
     */
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        $grid = new HistoryGrid();
        $grid->init($configuration, $language);
        $this->assertNotEmpty($grid->getColumns());
    }
}
