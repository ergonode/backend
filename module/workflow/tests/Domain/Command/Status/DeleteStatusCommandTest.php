<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteStatusCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var StatusId $id */
        $id = $this->createMock(StatusId::class);
        $code = 'Any code';

        $command = new DeleteStatusCommand($id);
        $this->assertSame($id, $command->getId());
    }
}
