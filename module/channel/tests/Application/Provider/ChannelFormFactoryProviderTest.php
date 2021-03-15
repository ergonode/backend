<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Application\Provider;

use Ergonode\Channel\Application\Provider\ChannelFormFactoryProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;

class ChannelFormFactoryProviderTest extends TestCase
{
    public function testNotExist(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new ChannelFormFactoryProvider();
        $provider->provide('Any not supported form type');
    }

    public function testExist(): void
    {
        $factory = $this->createMock(ChannelFormFactoryInterface::class);
        $factory->method('supported')->willReturn(true);

        $provider = new ChannelFormFactoryProvider(...[$factory]);
        $result = $provider->provide('Any supported form type');
        self::assertSame($factory, $result);
    }
}
