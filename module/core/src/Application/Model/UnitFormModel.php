<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model;

use Ergonode\Core\Infrastructure\Validator\Constraint\UnitNameUnique;
use Ergonode\Core\Infrastructure\Validator\Constraint\UnitSymbolUnique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class UnitFormModel
{
    /**
     * @var null | string
     *
     * @UnitNameUnique()
     *
     * @Assert\NotBlank(message="Unit name is required"),
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Unit name is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public ?string $name;

    /**
     * @var null | string
     *
     * @UnitSymbolUnique()
     *
     * @Assert\NotBlank(message="Unit symbol is required"),
     * @Assert\Length(
     *     max=16,
     *     maxMessage="Unit symbol is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public ?string $symbol;

    /**
     */
    public function __construct()
    {
        $this->name = null;
        $this->symbol = null;
    }
}
