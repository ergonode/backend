<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Attribute;

use Symfony\Component\Validator\Constraints as Assert;

class AttributeValueTranslationFormModel
{
    /**
     * @Assert\Regex("/^[a-z]{2}_[A-Z]{2}$/")
     */
    private ?string $language = null;
}