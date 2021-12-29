<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Importer\Infrastructure\Grid\ImportErrorsGridBuilder;
use PHPUnit\Framework\TestCase;

class ImportErrorsGridBuilderTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $builder = new ImportErrorsGridBuilder();

        $grid = $builder->build($configuration, $language);

        self::assertNotEmpty($grid->getColumns());
    }
}
