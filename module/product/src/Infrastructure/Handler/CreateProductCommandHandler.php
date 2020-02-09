<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Product\Domain\Command\CreateProductCommand;
use Ergonode\Product\Domain\Provider\ProductFactoryProvider;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ProductSimple\Domain\Entity\SimpleProduct;
use Webmozart\Assert\Assert;

/**
 */
class CreateProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ProductFactoryProvider
     */
    private $productFactoryProvider;

    /**
     * @param ProductRepositoryInterface  $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductFactoryProvider      $productFactoryProvider
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ProductFactoryProvider $productFactoryProvider
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productFactoryProvider = $productFactoryProvider;
    }

    /**
     * @param CreateProductCommand $command
     */
    public function __invoke(CreateProductCommand $command)
    {
        try {
            $categories = [];
            foreach ($command->getCategories() as $categoryId) {
                $category = $this->categoryRepository->load($categoryId);
                Assert::notNull($category);
                $categories[] = $category->getCode();
            }

            $factory = $this->productFactoryProvider->provide(SimpleProduct::TYPE);
            $product = $factory->create($command->getId(), $command->getSku(), $categories, $command->getAttributes());

            $this->productRepository->save($product);
        } catch (\Throwable $exception) {
            echo print_r($exception->getMessage(), true);
            echo print_r($exception->getTraceAsString(), true);
            die;
        }
    }
}
