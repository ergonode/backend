<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Command;

use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteDesignerTemplateCommandTest extends TestCase
{
    public function testResultValues(): void
    {
        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new DeleteTemplateCommand($id);
        $this->assertSame($id, $command->getId());
    }
}
