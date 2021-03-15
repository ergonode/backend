<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Factory\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Application\Model\Product\Attribute\Delete\DeleteProductAttributeFormModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributesCommand;

class RemoveProductAttributeCommandFactory
{
    public function create(DeleteProductAttributeFormModel $model): RemoveProductAttributesCommand
    {
        $attributes = [];
        foreach ($model->payload as $attribute) {
            $value = [];
            foreach ($attribute->languages as $language) {
                $value[] = new Language($language);
            }
            $attributes[$attribute->id] = array_unique($value);
        }

        return new RemoveProductAttributesCommand(new ProductId($model->id), $attributes);
    }
}
