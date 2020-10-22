<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Command;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class CreateCategoryCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        $id = $this->createMock(CategoryId::class);
        $code = $this->createMock(CategoryCode::class);
        $name = $this->createMock(TranslatableString::class);

        $command = new CreateCategoryCommand($id, $code, $name);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
    }
}
