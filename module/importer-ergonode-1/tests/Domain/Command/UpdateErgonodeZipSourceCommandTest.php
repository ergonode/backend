<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Tests\Domain\Command;

use Ergonode\ImporterErgonode1\Domain\Command\UpdateErgonodeZipSourceCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use PHPUnit\Framework\TestCase;

final class UpdateErgonodeZipSourceCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $id = $this->createMock(SourceId::class);
        $name = 'Any name';
        $command = new UpdateErgonodeZipSourceCommand($id, $name);
        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
    }
}
