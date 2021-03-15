<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterMagento2\Domain\Command\UpdateMagento2ExportChannelCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class UpdateMagento2ExportChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    private string $name;
    private string $filename;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Name';
        $this->filename = 'anyfile.csv';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    public function testCreateCommand(): void
    {
        $command = new UpdateMagento2ExportChannelCommand(
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
