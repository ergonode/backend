<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Attribute\Update;

use Ergonode\Attribute\Domain\Command\Attribute\AbstractUpdateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class UpdateUnitAttributeCommand extends AbstractUpdateAttributeCommand
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $unitId;

    /**
     * @param AttributeGroupId[] $groups
     */
    public function __construct(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        UnitId $unitId,
        array $groups = []
    ) {
        parent::__construct(
            $id,
            $label,
            $hint,
            $placeholder,
            $scope,
            $groups
        );

        $this->unitId = $unitId;
    }

    public function getUnitId(): UnitId
    {
        return $this->unitId;
    }
}
