<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Factory\Command;

use Ergonode\Category\Application\Model\CategoryFormModel;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Infrastructure\Factory\Command\UpdateCategoryCommandFactory;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class UpdateCategoryCommandFactoryTest extends TestCase
{
    public function testSupported(): void
    {
        $commandFactory = new UpdateCategoryCommandFactory();
        $this->assertTrue($commandFactory->support(Category::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $name = ['pl_PL' => 'Any Name'];
        $data = $this->createMock(CategoryFormModel::class);
        $data->name = $name;

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new UpdateCategoryCommandFactory();
        $categorId = $this->createMock(CategoryId::class);

        /** @var UpdateCategoryCommand $command */
        $command = $commandFactory->create($categorId, $form);
        $this->assertEquals($command->getName(), new TranslatableString($name));
        $this->assertEquals($command->getId(), $categorId);
    }
}
