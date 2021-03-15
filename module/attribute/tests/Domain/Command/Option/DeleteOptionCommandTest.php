<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Option;

use Ergonode\Attribute\Domain\Command\Option\DeleteOptionCommand;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteOptionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testUpdateCommand(): void
    {
        /** @var AggregateId $id */
        $id =  $this->createMock(AggregateId::class);
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);

        $command = new DeleteOptionCommand($id, $attributeId);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($attributeId, $command->getAttributeId());
    }
}
