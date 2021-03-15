<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use PHPUnit\Framework\TestCase;

class UpdateDesignerTemplateCommandTest extends TestCase
{
    public function testResultValues(): void
    {
        /** @var TemplateId $id */
        $id = $this->createMock(TemplateId::class);
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $elements->add($this->createMock(TemplateElementInterface::class));
        /** @var MultimediaId $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        /** @var AttributeId $defaultText */
        $defaultText = $this->createMock(AttributeId::class);
        /** @var AttributeId $defaultImage */
        $defaultImage = $this->createMock(AttributeId::class);

        $command = new UpdateTemplateCommand($id, $name, $elements, $defaultText, $defaultImage, $multimediaId);
        $this->assertSame($id, $command->getId());
        $this->assertSame($name, $command->getName());
        $this->assertSame($multimediaId, $command->getImageId());
        $this->assertSame($defaultText, $command->getDefaultLabel());
        $this->assertSame($defaultImage, $command->getDefaultImage());
        $this->assertSame($elements, $command->getElements());
    }
}
