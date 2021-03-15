<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Tests\Domain\Command;

use Ergonode\ImporterMagento1\Domain\Command\CreateMagento1CsvSourceCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Core\Domain\ValueObject\Language;

class CreateMagento1CsvSourceCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(SourceId::class);
        $name = 'Any name';
        $defaultLanguage = $this->createMock(Language::class);
        $command = new CreateMagento1CsvSourceCommand($id, $name, $defaultLanguage);
        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
        self::assertEquals($defaultLanguage, $command->getDefaultLanguage());
    }
}
