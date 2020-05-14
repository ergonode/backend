<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class CreatedAtSystemAttribute extends DateAttribute implements AttributeInterface
{
    public const TYPE = 'DATE';
    public const CODE = 'esa_created_at';

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
        $format = new DateFormat(DateFormat::YYYY_MM_DD);

        parent::__construct($id, $code, $label, $hint, $placeholder, false, $format);
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
}
