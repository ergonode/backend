<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
final class CategoryImportAction
{
    /**
     * @var CategoryQueryInterface
     */
    private CategoryQueryInterface $query;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * @param CategoryQueryInterface      $query
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(
        CategoryQueryInterface $query,
        CategoryRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->repository = $repository;
    }

    /**
     * @param CategoryCode       $code
     * @param TranslatableString $name
     *
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
