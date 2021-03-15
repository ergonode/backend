<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractImageAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DefaultImageSystemAttribute extends AbstractImageAttribute
{
    public const TYPE = 'IMAGE';
    public const CODE = 'esa_default_image';


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

    public function isMultilingual(): bool
    {
        return false;
    }
}
