<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Provider;

use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;
use PHPUnit\Framework\MockObject\MockObject;

class WriterProviderTest extends TestCase
{
    /**
     * @var WriterInterface|MockObject
     */
    private WriterInterface $interface;

    protected function setUp(): void
    {
        $this->interface = $this->createMock(WriterInterface::class);
    }

    public function testFindWriter(): void
    {
        $this->interface->expects($this->once())->method('support')->willReturn(true);

        $provider = new WriterProvider(...[$this->interface]);
        $result = $provider->provide('type');
        self::assertSame($this->interface, $result);
    }

    public function testNotFindWriter(): void
    {
        $this->expectException(\RuntimeException::class);
        $interface = $this->createMock(WriterInterface::class);
        $interface->expects($this->once())->method('support')->willReturn(false);

        $provider = new WriterProvider(...[$interface]);
        $provider->provide('type');
    }
}
