<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Domain\Command\Import;

use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

/**
 */
class ImportVariableProductCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        $importId = $this->createMock(ImportId::class);
        $sku = $this->createMock(Sku::class);
        $template = 'code template';
        $categories = [$this->createMock(CategoryCode::class)];
        $attributes = ['code' => $this->createMock(TranslatableString::class)];
        $bindings = [$this->createMock(AttributeCode::class)];
        $children = [$this->createMock(Sku::class)];

        $command = new ImportVariableProductCommand(
            $importId,
            $sku,
            $template,
            $categories,
            $bindings,
            $children,
            $attributes
        );
        self::assertSame($importId, $command->getImportId());
        self::assertSame($sku, $command->getSku());
        self::assertSame($template, $command->getTemplate());
        self::assertSame($categories, $command->getCategories());
        self::assertSame($attributes, $command->getAttributes());
        self::assertSame($bindings, $command->getBindings());
        self::assertSame($children, $command->getChildren());
    }
}
