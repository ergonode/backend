<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Service\Thumbnail;

use Ergonode\Multimedia\Infrastructure\Service\Thumbnail\ThumbnailGenerationStrategyProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Infrastructure\Service\Thumbnail\ThumbnailGenerationStrategyInterface;

class ThumbnailGenerationStrategyProviderTest extends TestCase
{
    public function testNotExistStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $provider = new ThumbnailGenerationStrategyProvider();
        $provider->provide('Any not supported strategy type');
    }

    public function testExistStrategy(): void
    {
        $strategy = $this->createMock(ThumbnailGenerationStrategyInterface::class);
        $strategy->method('supported')->willReturn(true);

        $provider = new ThumbnailGenerationStrategyProvider(...[$strategy]);
        $result = $provider->provide('Any not supported strategy type');
        self::assertSame($strategy, $result);
    }
}
