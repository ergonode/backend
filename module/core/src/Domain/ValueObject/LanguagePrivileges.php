<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

class LanguagePrivileges
{
    private bool $read;

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
