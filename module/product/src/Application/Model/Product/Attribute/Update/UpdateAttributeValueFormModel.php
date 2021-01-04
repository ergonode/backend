<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Update;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;

class UpdateAttributeValueFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=true)
     * @AttributeExists()
     */
    public ?string $id = null;

    /**
     * @Assert\Valid()
     *
     * @var UpdateAttributeValueTranslationFormModel[]
     */
    public array $values = [];
}
