<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class ProductTypeSystemAttribute extends AbstractTextAttribute
{
    public const TYPE = 'TEXT';
    public const CODE = 'esa_product_type';

    /**
     * @throws \Exception
     */
    public function __construct(
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder
    ) {
        $scope = new AttributeScope(AttributeScope::GLOBAL);
        $code = new AttributeCode(self::CODE);
        $id = AttributeId::fromKey($code->getValue());

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
