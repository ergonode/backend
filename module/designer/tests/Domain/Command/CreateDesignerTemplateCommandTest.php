<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateDesignerTemplateCommandTest extends TestCase
{
    /**
     */
    public function testCreateCommand(): void
    {
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $elements->add($this->createMock(TemplateElement::class));

        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new CreateTemplateCommand($name, $elements);
        $this->assertInstanceOf(TemplateId::class, $id);
        $this->assertSame($name, $command->getName());
        $this->assertSame($elements, $command->getElements());
    }
}
