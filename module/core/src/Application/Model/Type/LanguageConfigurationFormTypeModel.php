<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Validator\Constraint as CoreAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class LanguageConfigurationFormTypeModel
{
    /**
     * @var Language
     *
     * @Assert\NotBlank(message="Lanugage code is required")
     * @Assert\Length(max=2)
     *
     * @CoreAssert\LanguageCodeConstraint();
     */
    public ?Language $code = null;

    /**
     * @var bool
     *
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $active = null;
}
