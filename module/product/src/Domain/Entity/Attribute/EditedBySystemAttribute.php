<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

/**
 */
class EditedBySystemAttribute extends TextAttribute
{
    public const TYPE = 'TEXT';
    public const CODE = 'esa_edited_by';

    /**
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     *
     * @throws \Exception
     */
    public function __construct(
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder
    ) {
        $code = new AttributeCode(self::CODE);
        $id = AttributeId::fromKey($code->getValue());
        $scope = new AttributeScope(AttributeScope::GLOBAL);

        parent::__construct($id, $code, $label, $hint, $placeholder, $scope);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return false;
    }
}
