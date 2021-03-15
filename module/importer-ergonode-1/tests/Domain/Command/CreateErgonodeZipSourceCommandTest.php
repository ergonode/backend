<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Tests\Domain\Command;

use Ergonode\ImporterErgonode1\Domain\Command\CreateErgonodeZipSourceCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use PHPUnit\Framework\TestCase;

final class CreateErgonodeZipSourceCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(SourceId::class);
        $name = 'Any name';
        $command = new CreateErgonodeZipSourceCommand($id, $name);
        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
    }
}
