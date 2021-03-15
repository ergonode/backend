<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Command\Import\ImportSimpleProductCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportSimpleProductCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $id = $this->createMock(ImportLineId::class);
        $importId = $this->createMock(ImportId::class);
        $sku = 'Any sku';
        $template = 'code template';
        $categories = ['Any Category Code'];
        $attributes = ['code' => $this->createMock(TranslatableString::class)];

        $command = new ImportSimpleProductCommand($id, $importId, $sku, $template, $categories, $attributes);
        self::assertSame($id, $command->getId());
        self::assertSame($importId, $command->getImportId());
        self::assertSame($sku, $command->getSku());
        self::assertSame($template, $command->getTemplate());
        self::assertSame($categories, $command->getCategories());
        self::assertSame($attributes, $command->getAttributes());
    }
}
