<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Application\Provider;

use Ergonode\Product\Application\Provider\ProductTypeProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Entity\GroupingProduct;

class ProductTypeProviderTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testProvide(): void
    {
        $types = [SimpleProduct::class, GroupingProduct::class];
        $provider = new ProductTypeProvider(...$types);
        $this->assertSame([SimpleProduct::TYPE, GroupingProduct::TYPE], $provider->provide());
    }
}
