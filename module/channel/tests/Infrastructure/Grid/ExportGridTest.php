<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Infrastructure\Grid;

use Ergonode\Channel\Infrastructure\Grid\ExportGridBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class ExportGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        $builder = new ExportGridBuilder();
        $grid = $builder->build($configuration, $language);
        self::assertNotEmpty($grid->getColumns());
    }
}
