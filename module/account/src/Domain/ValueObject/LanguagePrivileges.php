<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

use JMS\Serializer\Annotation as JMS;

class LanguagePrivileges
{
    /**
     * @JMS\Type("bool")
     */
    private bool $read;

    /**
     * @JMS\Type("bool")
     */
    private bool $edit;

    public function __construct(bool $read, bool $edit)
    {
        $this->read = $read;
        $this->edit = $edit;
    }

    public function isEqual(LanguagePrivileges $value): bool
    {
        return $value->isReadable() === $this->read && $value->isEditable() === $this->edit;
    }

    public function isEditable(): bool
    {
        return $this->edit;
    }

    public function isReadable(): bool
    {
        return $this->read;
    }
}
