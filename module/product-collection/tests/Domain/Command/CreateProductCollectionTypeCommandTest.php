<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateProductCollectionTypeCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ProductCollectionTypeCode | MockObject $code */
        $code = $this->createMock(ProductCollectionTypeCode::class);

        /** @var TranslatableString | MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $command = new CreateProductCollectionTypeCommand($code, $name);

        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
    }
}
