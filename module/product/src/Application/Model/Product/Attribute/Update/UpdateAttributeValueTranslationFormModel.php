<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Update;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Infrastructure\Validator\Constraint as LanguageAssert;

class UpdateAttributeValueTranslationFormModel
{
    /**
     * @Assert\NotBlank()
     * @LanguageAssert\LanguageCodeConstraint()
     * @LanguageAssert\LanguageCodeActive()
     */
    public ?string $language = null;

    /**
     * @var mixed
     */
    public $value = null;
}
