<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportCategoryCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $id = $this->createMock(ImportLineId::class);
        $importId = $this->createMock(ImportId::class);
        $code = 'any code';
        $name = $this->createMock(TranslatableString::class);

        $command = new ImportCategoryCommand($id, $importId, $code, $name);
        self::assertSame($id, $command->getId());
        self::assertSame($importId, $command->getImportId());
        self::assertSame($code, $command->getCode());
        self::assertSame($name, $command->getName());
    }
}
