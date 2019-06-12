<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateDesignerTemplateCommandTest extends TestCase
{
    /**
     */
    public function testResultValues(): void
    {
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $sections = new ArrayCollection();

        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new CreateTemplateCommand($name, $elements, $sections);
        $this->assertInstanceOf(TemplateId::class, $id);
        $this->assertSame($name, $command->getName());
        $this->assertSame($elements, $command->getElements());
        $this->assertSame($sections, $command->getSections());
    }
}
