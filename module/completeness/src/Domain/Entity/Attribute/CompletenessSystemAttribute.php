<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class CompletenessSystemAttribute extends AbstractNumericAttribute
{
    public const TYPE = 'COMPLETENESS';

    public const CODE = 'esa_completeness';

    /**
     * @throws \Exception
     */
    public function __construct(
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder
    ) {
        $code = new AttributeCode(self::CODE);
        $id = AttributeId::fromKey($code->getValue());
        $scope = new AttributeScope(AttributeScope::LOCAL);

        parent::__construct($id, $code, $label, $hint, $placeholder, $scope);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function isSystem(): bool
    {
        return true;
    }

    public function isEditable(): bool
    {
        return false;
    }
}
