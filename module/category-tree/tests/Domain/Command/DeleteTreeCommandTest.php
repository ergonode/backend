<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Tests\Domain\Command;

use Ergonode\CategoryTree\Domain\Command\DeleteTreeCommand;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteTreeCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        /** @var CategoryTreeId | MockObject $id */
        $id = $this->createMock(CategoryTreeId::class);

        $command = new DeleteTreeCommand($id);

        $this->assertSame($id, $command->getId());
    }
}
