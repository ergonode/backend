<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model;

use Ergonode\Core\Infrastructure\Validator\Constraint\UnitForm;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UnitForm()
 */
class UnitFormModel
{
    /**
     * @var UnitId|null
     */
    private ?UnitId $unitId;

    /**
     * @var null | string
     *
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
     *
     * @Assert\Length(
     *     max=16,
     *     maxMessage="Unit symbol is to long, It should have {{ limit }} character or less."
     * )
     * })
     */
    public ?string $symbol;

    /**
     * @param UnitId|null $unitId
     */
    public function __construct(UnitId $unitId = null)
    {
        $this->unitId = $unitId;
        $this->name = null;
        $this->symbol = null;
    }

    /**
     * @return UnitId|null
     */
    public function getUnitId(): ?UnitId
    {
        return $this->unitId;
    }
}
