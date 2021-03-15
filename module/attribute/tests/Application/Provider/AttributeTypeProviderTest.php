<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Application\Provider;

use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;
use PHPUnit\Framework\TestCase;

class AttributeTypeProviderTest extends TestCase
{
    public function testEmptyType(): void
    {
        $provider = new AttributeTypeProvider();
        $this->assertEmpty($provider->provide());
    }

    public function testTypeProviding(): void
    {
        $types = ['type 1', 'type 2'];
        $provider = new AttributeTypeProvider(...$types);
        $this->assertSame($types, $provider->provide());
    }
}
