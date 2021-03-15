<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Factory\Command;

use Ergonode\Product\Domain\Command\Attribute\ChangeProductAttributesCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Application\Model\Product\Attribute\Update\UpdateProductAttributeFormModel;

class ChangeProductAttributeCommandFactory
{
    public function create(UpdateProductAttributeFormModel $model): ChangeProductAttributesCommand
    {
        $attributes = [];
        foreach ($model->payload as $attribute) {
            $value = [];
            foreach ($attribute->values as $translation) {
                $value[$translation->language] = $translation->value;
            }
            $attributes[$attribute->id] = $value;
        }

        return new ChangeProductAttributesCommand(new ProductId($model->id), $attributes);
    }
}
