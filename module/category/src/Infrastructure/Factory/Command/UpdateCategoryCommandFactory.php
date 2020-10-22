<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Factory\Command;

use Ergonode\Category\Application\Model\CategoryFormModel;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Symfony\Component\Form\FormInterface;

class UpdateCategoryCommandFactory implements UpdateCategoryCommandFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return $type === Category::TYPE;
    }

    /**
     * @param CategoryId    $id
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     */
    public function create(CategoryId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var CategoryFormModel $data */
        $data = $form->getData();

        return new UpdateCategoryCommand(
            $id,
            new TranslatableString($data->name),
        );
    }
}
