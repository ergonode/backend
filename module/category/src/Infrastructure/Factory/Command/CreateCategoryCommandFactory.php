<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Factory\Command;

use Ergonode\Category\Application\Model\CategoryFormModel;
use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Command\CreateCategoryCommandInterface;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Symfony\Component\Form\FormInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

class CreateCategoryCommandFactory implements CreateCategoryCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === Category::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateCategoryCommandInterface
    {
        /** @var CategoryFormModel $data */
        $data = $form->getData();

        return new CreateCategoryCommand(
            CategoryId::generate(),
            new CategoryCode($data->code),
            new TranslatableString($data->name)
        );
    }
}
