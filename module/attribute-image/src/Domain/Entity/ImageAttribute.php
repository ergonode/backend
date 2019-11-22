<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class ImageAttribute extends AbstractAttribute
{
    public const TYPE = 'IMAGE';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $system
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $system = false
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, false, [], $system);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return ArrayCollection|ImageFormat[]
     *
     * @todo remove
     */
    public function getFormats(): ArrayCollection
    {
        return [];
    }
}
