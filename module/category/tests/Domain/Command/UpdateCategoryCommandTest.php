<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Command;

use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class UpdateCategoryCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $id = $this->createMock(CategoryId::class);
        $name = $this->createMock(TranslatableString::class);

        $command = new UpdateCategoryCommand($id, $name);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
    }
}
