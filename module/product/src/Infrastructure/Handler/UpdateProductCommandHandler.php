<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Command\UpdateProductCommand;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * Class UpdateProductCommandHandler
 */
class UpdateProductCommandHandler
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
     * @param ProductRepositoryInterface  $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param UpdateProductCommand $command
     */
    public function __invoke(UpdateProductCommand $command)
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

        $categories = [];
        foreach ($command->getCategories() as $categoryId) {
            $category = $this->categoryRepository->load(new CategoryId($categoryId));
            Assert::notNull($category);
            $code = $category->getCode();
            $categories[$code->getValue()] = $code;
        }

        foreach ($categories as $categoryCode) {
            if (!$product->belongToCategory($categoryCode)) {
                $product->addToCategory($categoryCode);
            }
        }

        foreach ($product->getCategories() as $categoryCode) {
            if (!isset($categories[$categoryCode->getValue()])) {
                $product->removeFromCategory($categoryCode);
            }
        }

        $this->productRepository->save($product);
    }
}
