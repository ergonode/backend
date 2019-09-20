<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateLanguageCommand
{
    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private $code;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private $active;

    /**
     * @param Language $code
     * @param bool     $active
     */
    public function __construct(Language $code, bool $active)
    {
        $this->code = $code;
        $this->active = $active;
    }

    /**
     * @return Language
     */
    public function getCode(): Language
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}
