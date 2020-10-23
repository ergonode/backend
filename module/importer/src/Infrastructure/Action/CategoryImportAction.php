<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;

class CategoryImportAction
{
    private CategoryQueryInterface $query;

    private CategoryRepositoryInterface $repository;

    public function __construct(
        CategoryQueryInterface $query,
        CategoryRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function action(CategoryCode $code, TranslatableString $name): void
    {
        $categoryId = $this->query->findIdByCode($code);

        if (!$categoryId) {
            $category = new Category(
                CategoryId::generate(),
                $code,
                $name
            );
        } else {
            $category = $this->repository->load($categoryId);
            $category->changeName($name);
        }

        $this->repository->save($category);
    }
}
