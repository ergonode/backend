<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProductCollectionElementCreateFormModel
{
    /**
     * @Assert\NotBlank(message="Product id is required")
     * @Assert\Uuid(message="Product id must be valid uuid format")
     */
    public ?ProductId $productId;

    /**
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $visible;

    public function __construct()
    {
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
        if (($data->productId instanceof ProductId) && $data->productCollection->hasElement($data->productId)) {
            $context->addViolation('Element exists');
        }
    }
}
