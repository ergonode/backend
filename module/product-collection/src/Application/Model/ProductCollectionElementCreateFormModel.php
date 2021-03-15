<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Ergonode\Product\Application\Validator as ProductAssert;

class ProductCollectionElementCreateFormModel
{
    /**
     * @Assert\NotBlank(message="Product id is required")
     * @Assert\Uuid(strict=true, message="Product id must be valid uuid format")
     * @ProductAssert\ProductExists()
     */
    public ?string $productId;

    /**
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $visible;

    private ProductCollection $productCollection;

    public function __construct(ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
        $this->productId = null;
        $this->visible = null;
    }

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context): void
    {
        /** @var ProductCollectionElementCreateFormModel $data */
        $data = $context->getValue();
        if (ProductId::isValid($data->productId)
            && $data->getProductCollection()->hasElement(new ProductId($data->productId))
        ) {
            $context->addViolation('Element exists');
        }
    }

    public function getProductCollection(): ProductCollection
    {
        return $this->productCollection;
    }
}
