<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Segment\Domain\Command\CreateSegmentCommand;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateSegmentCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        /** @var ConditionSetId|MockObject $conditionSetId */
        $conditionSetId = $this->createMock(ConditionSetId::class);
        /** @var SegmentCode|MockObject $code */
        $code = $this->createMock(SegmentCode::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);

        $command = new CreateSegmentCommand($code, $name, $description, $conditionSetId);
        $this->assertNotNull($command->getId());
        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
        $this->assertEquals($description, $command->getDescription());
        $this->assertEquals($conditionSetId, $command->getConditionSetId());
    }
}
