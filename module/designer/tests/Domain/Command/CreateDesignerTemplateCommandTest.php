<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\ValueObject\TemplateCode;

class CreateDesignerTemplateCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $id = $this->createMock(TemplateId::class);
        $code = $this->createMock(TemplateCode::class);
        $name = 'Any Name';
        $elements = new ArrayCollection();
        $elements->add($this->createMock(TemplateElementInterface::class));
        $multimediaId = $this->createMock(MultimediaId::class);
        $defaultLabel = $this->createMock(AttributeId::class);
        $defaultImage = $this->createMock(AttributeId::class);


        $command = new CreateTemplateCommand($name, $code, $elements, $defaultLabel, $defaultImage, $multimediaId);
        $this->assertInstanceOf(TemplateId::class, $id);
        $this->assertSame($name, $command->getName());
        $this->assertSame($code, $command->getCode());
        $this->assertSame($elements, $command->getElements());
        $this->assertSame($multimediaId, $command->getImageId());
        $this->assertSame($defaultLabel, $command->getDefaultLabel());
        $this->assertSame($defaultImage, $command->getDefaultImage());
        $this->assertNotNull($command->getId());
    }
}
