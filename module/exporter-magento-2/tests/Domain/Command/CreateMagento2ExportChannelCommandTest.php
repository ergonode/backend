<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportChannelCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class CreateMagento2ExportChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

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
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Name';
        $this->filename = 'anyfile.csv';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    /**
     */
    public function testCreateCommand(): void
    {
        $command = new CreateMagento2ExportChannelCommand(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        self::assertEquals($this->id, $command->getId());
        self::assertEquals($this->name, $command->getName());
        self::assertEquals($this->filename, $command->getFilename());
        self::assertEquals($this->defaultLanguage, $command->getDefaultLanguage());
    }
}
