<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\AbstractCreateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;

class CreateUnitAttributeCommand extends AbstractCreateAttributeCommand
{
    /**
     * @var UnitId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $unitId;

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param UnitId             $unitId
     * @param array              $groups
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        UnitId $unitId,
        array $groups = []
    ) {
        parent::__construct(
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            $groups
        );

        $this->unitId = $unitId;
    }

    /**
     * @return UnitId
     */
    public function getUnitId(): UnitId
    {
        return $this->unitId;
    }
}
