<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Product;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use PHPUnit\Framework\TestCase;

class Shopware6ProductCategoryTest extends TestCase
{
    private string $id;

    protected function setUp(): void
    {
        $this->id = 'any_id';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6ProductCategory($this->id);

        self::assertEquals($this->id, $model->getId());
    }
}
