<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Update;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductAttributeCollectionFormModel
{
    /**
     * @Assert\Valid()
     *
     * @var UpdateProductAttributeFormModel[]
     */
    public array $data = [];
}
