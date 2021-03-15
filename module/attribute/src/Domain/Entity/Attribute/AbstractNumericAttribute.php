<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

abstract class AbstractNumericAttribute extends AbstractAttribute
{
    public const TYPE = 'NUMERIC';

    /**
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, $scope);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
