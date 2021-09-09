<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Factory\Command;

use Ergonode\Category\Application\Model\CategoryFormModel;
use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Infrastructure\Factory\Command\CreateCategoryCommandFactory;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class CreateCategoryCommandFactoryTest extends TestCase
{
    public function testSupported(): void
    {
        $commandFactory = new CreateCategoryCommandFactory();
        $this->assertTrue($commandFactory->support(Category::TYPE));
        $this->assertFalse($commandFactory->support('Any other type'));
    }

    public function testCreation(): void
    {
        $name = ['pl_PL' => 'Any Name'];
        $code = 'Any_category';
        $data = $this->createMock(CategoryFormModel::class);
        $data->name = $name;
        $data->code = $code;

        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateCategoryCommandFactory();

        /** @var CreateCategoryCommand $command */
        $command = $commandFactory->create($form);
        $this->assertEquals($command->getName(), new TranslatableString($name));
        $this->assertEquals($command->getCode(), new CategoryCode($code));
    }
}
