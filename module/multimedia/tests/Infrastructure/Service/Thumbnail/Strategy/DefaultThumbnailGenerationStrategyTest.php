<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Service\Thumbnail\Strategy;

use Ergonode\Multimedia\Infrastructure\Service\Thumbnail\Strategy\DefaultThumbnailGenerationStrategy;
use PHPUnit\Framework\TestCase;

class DefaultThumbnailGenerationStrategyTest extends TestCase
{
    public function testSupportedType(): void
    {
        $strategy = new DefaultThumbnailGenerationStrategy();

        self::assertTrue($strategy->supported('default'));
    }

    public function testNotSupportedType(): void
    {
        $strategy = new DefaultThumbnailGenerationStrategy();

        self::assertFalse($strategy->supported('any non supported type'));
    }

    public function testGenerationThumbnail(): void
    {
        $imagick = $this->createMock(\Imagick::class);
        $imagick->expects(self::once())->method('getImageWidth')->willReturn(1000);
        $imagick->expects(self::once())->method('scaleImage');
        $strategy = new DefaultThumbnailGenerationStrategy();
        $strategy->generate($imagick);
    }

    public function testLeaveOriginalImage(): void
    {
        $imagick = $this->createMock(\Imagick::class);
        $imagick->expects(self::once())->method('getImageWidth')->willReturn(800);
        $imagick->expects(self::never())->method('scaleImage');
        $strategy = new DefaultThumbnailGenerationStrategy();
        $strategy->generate($imagick);
    }
}
