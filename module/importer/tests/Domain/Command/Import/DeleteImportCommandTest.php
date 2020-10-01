<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\Import\DeleteImportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteImportCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        $importId = $this->createMock(ImportId::class);

        $command = new DeleteImportCommand($importId);
        self::assertSame($importId, $command->getId());
    }
}
