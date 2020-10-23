<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Command;

use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use PHPUnit\Framework\TestCase;

class DeleteSourceCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $sourceId = $this->createMock(SourceId::class);

        $command = new DeleteSourceCommand($sourceId);
        self::assertSame($sourceId, $command->getId());
    }
}
