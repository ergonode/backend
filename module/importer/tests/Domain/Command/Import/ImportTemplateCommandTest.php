<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportTemplateCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $id = $this->createMock(ImportLineId::class);
        $importId = $this->createMock(ImportId::class);
        $code = 'any code';

        $command = new ImportTemplateCommand($id, $importId, $code);
        self::assertSame($id, $command->getId());
        self::assertSame($importId, $command->getImportId());
        self::assertSame($code, $command->getCode());
    }
}
