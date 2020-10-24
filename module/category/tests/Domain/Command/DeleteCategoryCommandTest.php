<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Domain\Command;

use Ergonode\Category\Domain\Command\DeleteCategoryCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\TestCase;

class DeleteCategoryCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        $id = $this->createMock(CategoryId::class);

        $command = new DeleteCategoryCommand($id);
        $this->assertEquals($id, $command->getId());
    }
}
