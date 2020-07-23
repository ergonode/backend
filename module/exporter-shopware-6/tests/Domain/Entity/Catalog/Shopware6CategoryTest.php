<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Entity\Catalog;

use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6CategoryTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var ExportCategory|MockObject
     */
    private ExportCategory $category;

    /**
     */
    protected function setUp(): void
    {
        $this->id = 'Any Id';
        $this->category = $this->createMock(ExportCategory::class);
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new Shopware6Category(
            $this->id,
            $this->category
        );

        self::assertEquals($this->id, $entity->getId());
        self::assertEquals($this->category, $entity->getCategory());
    }
}
