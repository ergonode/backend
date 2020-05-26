<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Application\Model;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;

/**
 */
class ExporterFileConfigurationModelTest extends TestCase
{
    /**
     */
    public function testCreateWithoutProfile(): void
    {
        $model = new ExporterFileConfigurationModel();
        $this->assertNull($model->name);
        $this->assertNull($model->format);
    }

    /**
     */
    public function testCreateWithProfile(): void
    {
        $name = 'Name';
        $format = 'Format';
        $profile = $this->createMock(FileExportProfile::class);
        $profile->method('getName')->willReturn($name);
        $profile->method('getFormat')->willReturn($format);

        $model = new ExporterFileConfigurationModel($profile);
        $this->assertSame($name, $model->name);
        $this->assertSame($format, $model->format);
    }
}
