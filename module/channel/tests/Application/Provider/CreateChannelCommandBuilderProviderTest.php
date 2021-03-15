<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Application\Provider;

use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;

class CreateChannelCommandBuilderProviderTest extends TestCase
{
    public function testNotExist(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new CreateChannelCommandBuilderProvider();
        $provider->provide('Any not supported form type');
    }

    public function testExist(): void
    {
        $builder = $this->createMock(CreateChannelCommandBuilderInterface::class);
        $builder->method('supported')->willReturn(true);

        $provider = new CreateChannelCommandBuilderProvider(...[$builder]);
        $result = $provider->provide('Any supported form type');
        self::assertSame($builder, $result);
    }
}
