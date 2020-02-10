<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\CategoryTree\Infrastructure\Provider\CategoryTreeProvider;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;

/**
 */
class CategoryImportAction implements ImportActionInterface
{
    public const TYPE = 'CATEGORY';

    public const CODE_FIELD = 'code';
    public const NAME_FIELD = 'name';

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var CategoryTreeProvider
     */
    private CategoryTreeProvider $treeProvider;

    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $treeRepository;

    /**
     * @var CategoryFactory
     */
    private CategoryFactory $factory;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryTreeProvider        $treeProvider
     * @param TreeRepositoryInterface     $treeRepository
     * @param CategoryFactory             $factory
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryTreeProvider $treeProvider,
        TreeRepositoryInterface $treeRepository,
        CategoryFactory $factory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->treeProvider = $treeProvider;
        $this->treeRepository = $treeRepository;
        $this->factory = $factory;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        $code = $record->get(self::CODE_FIELD) ? new CategoryCode($record->get(self::CODE_FIELD)->getValue()) : null;
        $name = $record->get(self::NAME_FIELD) ? $record->get(self::NAME_FIELD)->getValue() : null;
        Assert::notNull($code, 'Category import required "code" field not exists');
        Assert::notNull($name, 'Category import required "name" field not exists');

        $data = [
            'attributes' => [],
        ];

        $tree = $this->treeProvider->getTree(CategoryTree::DEFAULT);

        $categoryId = CategoryId::fromCode($code->getValue());

        $parentCode = $record->get('parent')? new CategoryCode($record->get('parent')->getValue()): null;
        $parentId = $parentCode? CategoryId::fromCode($parentCode->getValue()): null;
        $category = $this->categoryRepository->load($categoryId);

        if (!$category) {
            $category = $this->factory->create($categoryId, $code, $name, $data['attributes']);
        }

        $this->categoryRepository->save($category);

        if (!$tree->hasCategory($categoryId)) {
            $tree->addCategory($category->getId(), $parentId);
        }

        $this->treeRepository->save($tree);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
