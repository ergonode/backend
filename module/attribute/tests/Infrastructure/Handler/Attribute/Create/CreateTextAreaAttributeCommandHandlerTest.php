<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Handler\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateTextareaAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\Create\CreateTextareaAttributeCommandHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateTextAreaAttributeCommandHandlerTest extends TestCase
{
    /**
     * @var CreateTextareaAttributeCommand|MockObject
     */
    private $command;

    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var AbstractAttribute|MockObject
     */
    private $attribute;

    protected function setUp(): void
    {
        $this->command = $this->createMock(CreateTextareaAttributeCommand::class);
        $this->command->method('getLabel')->willReturn(new TranslatableString());
        $this->command->method('getPlaceholder')->willReturn(new TranslatableString());
        $this->command->method('getHint')->willReturn(new TranslatableString());
        $this->repository = $this->createMock(AttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(AbstractAttribute::class);
    }

    public function testHandleCommand(): void
    {
        $this->repository->method('load')->willReturn($this->attribute);
        $this->repository->expects($this->once())->method('save');

        $handler = new CreateTextareaAttributeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
