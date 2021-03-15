<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Provider;

use Ergonode\ExporterFile\Infrastructure\Provider\WriterTypeProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;
use PHPUnit\Framework\MockObject\MockObject;

class WriterTypeProviderTest extends TestCase
{
    /**
     * @var WriterInterface|MockObject
     */
    private WriterInterface $interface;

    protected function setUp(): void
    {
        $this->interface = $this->createMock(WriterInterface::class);
    }

    public function testProvideWriterTypes(): void
    {
        $type = 'Any type';
        $this->interface->expects($this->once())->method('getType')->willReturn($type);

        $provider = new WriterTypeProvider(...[$this->interface]);
        $result = $provider->provide();
        self::assertSame([$type], $result);
    }
}
