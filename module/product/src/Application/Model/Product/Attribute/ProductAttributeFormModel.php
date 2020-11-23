<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute;

use Symfony\Component\Validator\Constraints as Assert;

class ProductAttributeFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=true)
     */
    public ?string $id = null;

    /**
     * @Assert\Valid()
     *
     * @var AttributeValueFormModel[]
     */
    public array $payload = [];
}
