<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Model;

use Ergonode\Core\Application\Validator\UnitForm;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UnitForm()
 */
class UnitFormModel
{
    private ?UnitId $unitId;

    /**
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Unit name is too long. It should contain {{ limit }} characters or less."
     * )
     * })
     */
    public ?string $name;

    /**
     * @Assert\Length(
     *     max=16,
     *     maxMessage="Unit symbol is too long. It should contain {{ limit }} characters or less."
     * )
     * })
     */
    public ?string $symbol;

    public function __construct(UnitId $unitId = null)
    {
        $this->unitId = $unitId;
        $this->name = null;
        $this->symbol = null;
    }

    public function getUnitId(): ?UnitId
    {
        return $this->unitId;
    }
}
