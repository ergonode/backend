<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\User\UserInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

abstract class AbstractValueCommandHandler
{
    /**
     * @throws \Exception
     */
    protected function attributeUpdate(AbstractProduct $product, AttributeCode $code, ValueInterface $value): void
    {
        if (!$product->hasAttribute($code)) {
            $product->addAttribute($code, $value);
        } else {
            $product->changeAttribute($code, $value);
        }
    }

    /**
     * @throws \Exception
     */
    protected function updateAudit(UserInterface $user, AbstractProduct $product): void
    {
        $updatedAt = new \DateTime();
        $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
        $editedAtCode = new AttributeCode(EditedAtSystemAttribute::CODE);
        $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
        $editedAtValue = new StringValue($updatedAt->format('Y-m-d H:i:sO'));
        $this->attributeUpdate($product, $editedByCode, $editedByValue);
        $this->attributeUpdate($product, $editedAtCode, $editedAtValue);
    }
}
