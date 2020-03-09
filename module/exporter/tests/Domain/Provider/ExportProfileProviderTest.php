<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Provider;

use Ergonode\Exporter\Domain\Factory\ExportProfileFactoryInterface;
use Ergonode\Exporter\Domain\Provider\ExportProfileProvider;
use PHPUnit\Framework\TestCase;

/**
 */
class ExportProfileProviderTest extends TestCase
{
    /**
     * @var ExportProfileFactoryInterface
     */
    private ExportProfileFactoryInterface $exporter1;

    /**
     * @var ExportProfileFactoryInterface
     */
    private ExportProfileFactoryInterface $exporter2;

    /**
     */
    protected function setUp(): void
    {
        $this->exporter1 = $this->createMock(ExportProfileFactoryInterface::class);
        $this->exporter2 = $this->createMock(ExportProfileFactoryInterface::class);
    }

    /**
     */
    public function testProvideExporterFactoryFirst(): void
    {
        $this->exporter1->method('supported')->willReturn(true);
        $this->exporter2->method('supported')->willReturn(false);
        $provider = new ExportProfileProvider($this->exporter1, $this->exporter2);

        $result = $provider->provide('any_correct_type');
        $this->assertSame($this->exporter1, $result);
        $this->assertNotSame($this->exporter2, $result);
    }

    /**
     */
    public function testProvideExporterFactorySecound(): void
    {
        $this->exporter1->method('supported')->willReturn(false);
        $this->exporter2->method('supported')->willReturn(true);
        $provider = new ExportProfileProvider($this->exporter1, $this->exporter2);

        $result = $provider->provide('any_correct_type');
        $this->assertNotSame($this->exporter1, $result);
        $this->assertSame($this->exporter2, $result);
    }

    /**
     */
    public function testProvideExporterFactoryNone(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->exporter1->method('supported')->willReturn(false);
        $this->exporter2->method('supported')->willReturn(false);
        $provider = new ExportProfileProvider($this->exporter1, $this->exporter2);

        $provider->provide('any_correct_type');
    }
}
