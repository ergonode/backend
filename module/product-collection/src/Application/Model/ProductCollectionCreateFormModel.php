<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeUnique;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeValid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ProductCollectionCreateFormModel
{
    /**
     * @var ProductCollectionCode | null
     *
     * @Assert\NotBlank(message="Category code is required")
     * @Assert\Length(max=64)
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$\b/i",
     *     message="Category code can have only letters, digits or underscore symbol"
     *  )
     *
     * @ProductCollectionCodeValid()
     *
     * @ProductCollectionCodeUnique()
     */
    public ?ProductCollectionCode $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=100,
     *     maxMessage="Product collection name is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public array $name;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=100,
     *     maxMessage="Product collection description is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public array $description;

    /**
     * @var ProductCollectionTypeId | null
     *
     * @Assert\NotBlank(message="Collection type id is required")
     * @Assert\Uuid(message="Collection type id must be valid uuid format")
     */
    public ?ProductCollectionTypeId $typeId;

    /**
     */
    public function __construct()
    {
        $this->name = [];
        $this->description = [];
        $this->code = null;
        $this->typeId = null;
    }
}
