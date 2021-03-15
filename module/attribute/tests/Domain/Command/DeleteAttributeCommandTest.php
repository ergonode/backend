<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command;

use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteAttributeCommandTest extends TestCase
{
    public function testDeleteEvent(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $command = new DeleteAttributeCommand($attributeId);

        $this->assertSame($attributeId, $command->getId());
    }
}
