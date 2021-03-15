<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Application\Provider;

use Ergonode\Category\Application\Provider\CategoryTypeProvider;
use PHPUnit\Framework\TestCase;

class CategoryTypeProviderTest extends TestCase
{
    public function testEmptyType(): void
    {
        $provider = new CategoryTypeProvider();
        $this->assertEmpty($provider->provide());
    }

    public function testTypeProviding(): void
    {
        $types = ['type 1', 'type 2'];
        $provider = new CategoryTypeProvider(...$types);
        $this->assertSame($types, $provider->provide());
    }
}
