<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateDesignerTemplateCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $elements->add($this->createMock(TemplateElementInterface::class));
        /** @var MultimediaId $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        /** @var AttributeId $defaultLabel */
        $defaultLabel = $this->createMock(AttributeId::class);
        /** @var AttributeId $defaultImage */
        $defaultImage = $this->createMock(AttributeId::class);

        /** @var TemplateId|MockObject $id */
        $id = $this->createMock(TemplateId::class);
        $command = new CreateTemplateCommand($name, $elements, $defaultLabel, $defaultImage, $multimediaId);
        $this->assertInstanceOf(TemplateId::class, $id);
        $this->assertSame($name, $command->getName());
        $this->assertSame($elements, $command->getElements());
        $this->assertSame($multimediaId, $command->getImageId());
        $this->assertSame($defaultLabel, $command->getDefaultLabel());
        $this->assertSame($defaultImage, $command->getDefaultImage());
        $this->assertNotNull($command->getId());
    }
}
