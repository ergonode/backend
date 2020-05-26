<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateMagento2ExportProfileCommandTest extends TestCase
{
    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $id;

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportProfileId::class);
        $this->name = 'Any Name';
        $this->filename = 'anyfile.csv';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    /**
     */
    public function testCreateCommand(): void
    {
        $command = new CreateMagento2ExportProfileCommand(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        $this->assertEquals($this->id, $command->getId());
        $this->assertEquals($this->name, $command->getName());
        $this->assertEquals($this->filename, $command->getFilename());
        $this->assertEquals($this->defaultLanguage, $command->getDefaultLanguage());
    }
}
