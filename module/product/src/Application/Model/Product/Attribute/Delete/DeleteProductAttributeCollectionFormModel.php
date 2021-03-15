<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute\Delete;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteProductAttributeCollectionFormModel
{
    /**
     * @Assert\Valid()
     *
     * @var DeleteProductAttributeFormModel[]
     */
    public array $data = [];
}
