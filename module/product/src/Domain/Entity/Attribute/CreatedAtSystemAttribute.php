<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractDateAttribute;

class CreatedAtSystemAttribute extends AbstractDateAttribute
{
    public const TYPE = 'DATE';
    public const CODE = 'esa_created_at';

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
        $format = new DateFormat(DateFormat::YYYY_MM_DD);
        $scope = new AttributeScope(AttributeScope::GLOBAL);

        parent::__construct($id, $code, $label, $hint, $placeholder, $scope, $format);
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
