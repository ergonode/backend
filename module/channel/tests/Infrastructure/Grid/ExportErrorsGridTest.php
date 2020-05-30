<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Tests\Infrastructure\Grid;

use Ergonode\Channel\Infrastructure\Grid\ExportErrorsGrid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class ExportErrorsGridTest extends TestCase
{
    /**
     */
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        $grid = new ExportErrorsGrid();
        $grid->init($configuration, $language);
        $this->assertNotEmpty($grid->getColumns());
    }
}
