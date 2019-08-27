<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

/**
 */
interface AttributeValidatorInterface
{
    /**
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     *
     * @return bool
     */
    public function isValid(AbstractAttribute $attribute, $value): bool;

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool;
}
