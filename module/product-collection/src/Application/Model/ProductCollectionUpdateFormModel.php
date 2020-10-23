<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCollectionUpdateFormModel
{
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
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=1000,
     *     maxMessage="Product collection description is too long. It should contain {{ limit }} characters or less."
     * )
     * })
     */
    public array $description;

    /**
     * @Assert\NotBlank(message="Collection type is required")
     * @Assert\Uuid(message="Collection type must be valid uuid format")
     */
    public ?ProductCollectionTypeId $typeId;

    public function __construct()
    {
        $this->name = [];
        $this->description = [];
        $this->typeId = null;
    }
}
