<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Update;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateAttributeValueTranslationFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^[a-z]{2}_[A-Z]{2}$/")
     */
    public ?string $language = null;

    /**
     * @var mixed
     */
    public $value = null;
}
