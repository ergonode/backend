<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductCollectionElementCreateFormModel
{
    /**
     * @var ProductId | null
     *
     * @Assert\NotBlank(message="Product id is required")
     * @Assert\Uuid(message="Product id must be valid uuid format")
     *
     */
    public ?ProductId $productId;

    /**
     * @var bool | null
     *
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $visible;

    /**
     * @var ProductCollection
     */
    private ProductCollection $productCollection;

    /**
     * @param ProductCollection $productCollection
     */
    public function __construct(ProductCollection $productCollection)
    {
        $this->productCollection = $productCollection;
        $this->productId = null;
        $this->visible = null;
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context): void
    {
        /** @var ProductCollectionElementCreateFormModel $data */
        $data = $context->getValue();
        if (($data->productId instanceof ProductId) && $data->productCollection->hasElement($data->productId)) {
            $context->addViolation('Element exists');
        }
    }
}
