<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Model\Product\Relation;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductChildFormModelTest extends TestCase
{
    public function testModelCreation(): void
    {
        $id = $this->createMock(ProductId::class);
        $model = new ProductChildFormModel($id);
        $this->assertSame($id, $model->getParentId());
    }
}
