<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeUnique;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeCodeValid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ProductCollectionTypeCreateFormModel
{
    /**
     * @var ProductCollectionTypeCode | null
     *
     * @Assert\NotBlank(message="Product collection type code is required")
     * @Assert\Length(
     *     max=64,
     *     maxMessage="Product collection type System Name is too long. It should contain {{ limit }} characters or less."
     *     )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$\b/i",
     *     message="Product collection type can have only letters, digits or underscore symbol"
     *  )
     *
     * @ProductCollectionTypeCodeValid()
     *
     * @ProductCollectionCodeUnique()
     */
    public ?ProductCollectionTypeCode $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=100,
     *     maxMessage="Product collection name is too long. It should contain {{ limit }} characters or less."
     * )
     * })
     */
    public array $name;
    /**
     */
    public function __construct()
    {
        $this->name = [];
        $this->code = null;
    }
}
