<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Domain\Entity;

use Ergonode\Importer\Domain\Entity\ImportError;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
class ImportErrorTest extends TestCase
{
    /**
     */
    public function testCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $line = 1;
        $step = 2;
        $message = 'Any message';

        $entity = new ImportError($importId, $step, $line, $message);
        self::assertEquals($importId, $entity->getImportId());
        self::assertEquals($line, $entity->getLine());
        self::assertEquals($step, $entity->getStep());
        self::assertEquals($message, $entity->getMessage());
    }
}
