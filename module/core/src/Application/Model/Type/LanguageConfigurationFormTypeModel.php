<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Application\Validator as CoreAssert;
use Symfony\Component\Validator\Constraints as Assert;

class LanguageConfigurationFormTypeModel
{
    /**
     * @Assert\NotBlank(message="Lanugage code is required")
     *
     * @CoreAssert\LanguageCode();
     */
    public ?Language $code = null;

    /**
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $active = null;
}
