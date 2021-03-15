<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateAttributeGroupCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        /** @var AttributeGroupId|MockObject $id */
        $id = $this->createMock(AttributeGroupId::class);
        /** @var TranslatableString|MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $command = new UpdateAttributeGroupCommand($id, $name);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($name, $command->getName());
    }
}
