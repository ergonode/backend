<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeUnique;
use Ergonode\SharedKernel\Application\Validator\SystemCodeConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeExists;

class ProductCollectionCreateFormModel
{
    /**
     * @Assert\NotBlank(message="System name is required")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-_]+$\b/i",
     *     message="Product collection System Name can have only letters, digits or underscore symbol"
     *  )
     *
     * @SystemCodeConstraint(max=64)
     *
     * @ProductCollectionCodeUnique()
     */
    public ?string $code;

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
     * @Assert\NotBlank(message="Collection type id is required")
     * @Assert\Uuid(strict=true, message="Collection type id must be valid uuid format")
     * @ProductCollectionTypeExists()
     */
    public ?string $typeId;

    public function __construct()
    {
        $this->name = [];
        $this->description = [];
        $this->code = null;
        $this->typeId = null;
    }
}
