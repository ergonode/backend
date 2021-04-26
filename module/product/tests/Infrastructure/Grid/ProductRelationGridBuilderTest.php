<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Infrastructure\Grid;

use Ergonode\Product\Infrastructure\Grid\ProductRelationGridBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;

class ProductRelationGridBuilderTest extends TestCase
{
    public function testGridInit(): void
    {
        $query = $this->createMock(TemplateQueryInterface::class);
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $builder = new ProductRelationGridBuilder($query);
        $grid = $builder->build($configuration, $language);

        self::assertNotEmpty($grid->getColumns());
    }
}
