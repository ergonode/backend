<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Provider;

use Ergonode\Category\Infrastructure\Factory\Command\CreateCategoryCommandFactoryInterface;
use Ergonode\Category\Infrastructure\Provider\CreateCategoryCommandFactoryProvider;
use PHPUnit\Framework\TestCase;

class CreateCategoryCommandFactoryProviderTest extends TestCase
{
    public function testFactoryProvide(): void
    {
        $factory = $this->createMock(CreateCategoryCommandFactoryInterface::class);
        $factory->method('support')->willReturn(true);
        $provider = new CreateCategoryCommandFactoryProvider(...[$factory]);

        $factoryProvided = $provider->provide('type');
        $this->assertEquals($factory, $factoryProvided);
    }

    public function testNotExistingFactory(): void
    {
        $this->expectException(\RuntimeException::class);
        $factory = $this->createMock(CreateCategoryCommandFactoryInterface::class);
        $factory->method('support')->willReturn(false);
        $provider = new CreateCategoryCommandFactoryProvider(...[$factory]);

        $factoryProvided = $provider->provide('type');
    }
}
