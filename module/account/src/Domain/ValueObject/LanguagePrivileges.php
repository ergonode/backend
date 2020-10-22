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
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $read;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $edit;

    /**
     * @param bool $read
     * @param bool $edit
     */
    public function __construct(bool $read, bool $edit)
    {
        $this->read = $read;
        $this->edit = $edit;
    }

    /**
     * @param LanguagePrivileges $value
     *
     * @return bool
     */
    public function isEqual(LanguagePrivileges $value): bool
    {
        return $value->isReadable() === $this->read && $value->isEditable() === $this->edit;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->edit;
    }

    /**
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->read;
    }
}
