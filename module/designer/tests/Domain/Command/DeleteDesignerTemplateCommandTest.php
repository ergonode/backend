<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\Command;

use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteDesignerTemplateCommandTest extends TestCase
{
    /**
     */
    public function testResultValues(): void
    {
        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new DeleteTemplateCommand($id);
        $this->assertSame($id, $command->getId());
    }
}
