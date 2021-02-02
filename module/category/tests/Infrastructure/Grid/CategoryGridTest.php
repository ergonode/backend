<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Grid;

use Ergonode\Category\Infrastructure\Grid\CategoryGridBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use PHPUnit\Framework\TestCase;

class CategoryGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $builder = new CategoryGridBuilder();
        $grid = $builder->build($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
