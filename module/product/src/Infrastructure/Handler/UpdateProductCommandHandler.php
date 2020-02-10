<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Product\Domain\Command\UpdateProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

/**
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
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param ProductRepositoryInterface  $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param TokenStorageInterface       $tokenStorage
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param UpdateProductCommand $command
     *
     * @throws \Exception
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

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $updatedAt = new \DateTime();
            $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
            $editedAtCode = new AttributeCode(EditedAtSystemAttribute::CODE);
            /** @var User $user */
            $user = $token->getUser();
            $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $editedAtValue = new StringValue($updatedAt->format('Y-m-d H:i:s'));
            $this->attributeUpdate($product, $editedByCode, $editedByValue);
            $this->attributeUpdate($product, $editedAtCode, $editedAtValue);
        }

        $this->productRepository->save($product);
    }

    /**
     * @param AbstractProduct $product
     * @param AttributeCode   $code
     * @param ValueInterface  $value
     *
     * @throws \Exception
     */
    private function attributeUpdate(AbstractProduct $product, AttributeCode $code, ValueInterface $value): void
    {
        if (!$product->hasAttribute($code)) {
            $product->addAttribute($code, $value);
        } else {
            $product->changeAttribute($code, $value);
        }
    }
}
