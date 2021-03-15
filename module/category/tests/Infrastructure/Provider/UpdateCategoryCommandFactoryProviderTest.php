<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Provider;

use Ergonode\Category\Infrastructure\Factory\Command\UpdateCategoryCommandFactoryInterface;
use Ergonode\Category\Infrastructure\Provider\UpdateCategoryCommandFactoryProvider;
use PHPUnit\Framework\TestCase;

class UpdateCategoryCommandFactoryProviderTest extends TestCase
{
    public function testFactoryProvide(): void
    {
        $factory = $this->createMock(UpdateCategoryCommandFactoryInterface::class);
        $factory->method('support')->willReturn(true);
        $provider = new UpdateCategoryCommandFactoryProvider(...[$factory]);

        $factoryProvided = $provider->provide('type');
        $this->assertEquals($factory, $factoryProvided);
    }

    public function testNotExistingFactory(): void
    {
        $this->expectException(\RuntimeException::class);
        $factory = $this->createMock(UpdateCategoryCommandFactoryInterface::class);
        $factory->method('support')->willReturn(false);
        $provider = new UpdateCategoryCommandFactoryProvider(...[$factory]);

        $provider->provide('type');
    }
}
