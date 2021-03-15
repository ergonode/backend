<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Application\Model;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExporterFileConfigurationModelTest extends TestCase
{
    public function testCreateWithoutProfile(): void
    {
        $model = new ExporterFileConfigurationModel();
        self::assertNull($model->name);
        self::assertNull($model->format);
    }

    public function testCreateWithProfile(): void
    {
        $name = 'Name';
        $format = 'Format';
        $channel = $this->createMock(FileExportChannel::class);
        $channel->method('getName')->willReturn($name);
        $channel->method('getFormat')->willReturn($format);

        $model = new ExporterFileConfigurationModel($channel);
        self::assertSame($name, $model->name);
        self::assertSame($format, $model->format);
    }
}
