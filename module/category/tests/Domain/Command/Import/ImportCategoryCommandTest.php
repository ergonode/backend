<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Command\Import;

use Ergonode\Category\Domain\Command\Import\ImportCategoryCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportCategoryCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $code = 'any code';
        $name = $this->createMock(TranslatableString::class);

        $command = new ImportCategoryCommand($importId, $code, $name);
        self::assertSame($importId, $command->getImportId());
        self::assertSame($code, $command->getCode());
        self::assertSame($name, $command->getName());
    }
}
