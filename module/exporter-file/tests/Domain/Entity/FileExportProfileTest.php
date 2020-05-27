<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Domain\Entity;

use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
class FileExportProfileTest extends TestCase
{
    /**
     * @var ExportProfileId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $format;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportProfileId::class);
        $this->name = 'Name';
        $this->format = 'Format';
    }

    /**
     */
    public function testProfileCreation(): void
    {
        $entity = new FileExportProfile($this->id, $this->name, $this->format);

        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->format, $entity->getFormat());
        $this->assertEquals(FileExportProfile::TYPE, $entity->getType());
    }

    /**
     */
    public function testFormatChange(): void
    {
        $format = 'new Format';
        $entity = new FileExportProfile($this->id, $this->name, $this->format);
        $this->assertEquals($this->format, $entity->getFormat());
        $entity->setFormat($format);
        $this->assertNotEquals($this->format, $entity->getFormat());
        $this->assertEquals($format, $entity->getFormat());
    }
}
