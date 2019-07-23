<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Command;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateCategoryCommandTest extends TestCase
{
    /**
     */
    public function testCommand(): void
    {
        $code = $this->createMock(CategoryCode::class);
        $name = $this->createMock(TranslatableString::class);

        $command = new CreateCategoryCommand($code, $name);
        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
    }
}
