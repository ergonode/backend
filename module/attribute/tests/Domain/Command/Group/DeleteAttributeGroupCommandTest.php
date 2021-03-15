<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteAttributeGroupCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        /** @var AttributeGroupId|MockObject $id */
        $id = $this->createMock(AttributeGroupId::class);

        $command = new DeleteAttributeGroupCommand($id);
        $this->assertEquals($id, $command->getId());
    }
}
