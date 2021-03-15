<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Update;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Application\Validator as ProductAssert;

class UpdateProductAttributeFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid(strict=true)
     *
     * @ProductAssert\ProductExists()
     */
    public ?string $id = null;

    /**
     * @Assert\Valid()
     *
     * @var UpdateAttributeValueFormModel[]
     */
    public array $payload = [];
}
