<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Attribute;

use Symfony\Component\Validator\Constraints as Assert;

class ProductAttributeCollectionFormModel
{
    /**
     * @Assert\Valid()
     *
     * @var ProductAttributeFormModel[]
     */
    public array $data = [];
}