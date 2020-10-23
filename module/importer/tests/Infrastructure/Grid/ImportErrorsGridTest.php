<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Importer\Infrastructure\Grid\ImportErrorsGrid;
use PHPUnit\Framework\TestCase;

class ImportErrorsGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $grid = new ImportErrorsGrid();

        $grid->init($configuration, $language);

        self::assertNotEmpty($grid->getColumns());
    }
}
