<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ProductCollectionUpdateFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=100,
     *      maxMessage="Product collection name is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public array $name;

    /**
     * @var ProductCollectionTypeId | null
     *
     * @Assert\NotBlank(message="Collection type is required")
     * @Assert\Uuid(message="Collection type must be valid uuid format")
     */
    public ?ProductCollectionTypeId $typeId;

    /**
     */
    public function __construct()
    {
        $this->name = [];
        $this->typeId = null;
    }
}
