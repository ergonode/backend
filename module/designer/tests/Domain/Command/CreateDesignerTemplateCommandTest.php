<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateDesignerTemplateCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $elements->add($this->createMock(TemplateElement::class));
        /** @var MultimediaId $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);

        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new CreateTemplateCommand($name, $elements, $multimediaId);
        $this->assertInstanceOf(TemplateId::class, $id);
        $this->assertSame($name, $command->getName());
        $this->assertSame($elements, $command->getElements());
        $this->assertSame($multimediaId, $command->getImageId());
        $this->assertNotNull($command->getId());
    }
}
