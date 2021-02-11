<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportOptionCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $code = 'Any attribute code';
        $key = 'Any option key';
        $name = $this->createMock(TranslatableString::class);

        $command = new ImportOptionCommand($importId, $code, $key, $name);
        self::assertSame($importId, $command->getImportId());
        self::assertSame($code, $command->getCode());
        self::assertSame($key, $command->getOptionKey());
        self::assertSame($name, $command->getTranslation());
    }
}
