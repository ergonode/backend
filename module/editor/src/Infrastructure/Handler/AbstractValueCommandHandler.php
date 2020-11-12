<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

abstract class AbstractValueCommandHandler
{
    /**
     * @throws \Exception
     */
    protected function attributeUpdate(ProductDraft $product, AttributeCode $code, ValueInterface $value): void
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
    protected function updateAudit(User $user, ProductDraft $draft): void
    {
        $updatedAt = new \DateTime();
        $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
        $editedAtCode = new AttributeCode(EditedAtSystemAttribute::CODE);
        $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
        $editedAtValue = new StringValue($updatedAt->format('Y-m-d H:i:sO'));
        $this->attributeUpdate($draft, $editedByCode, $editedByValue);
        $this->attributeUpdate($draft, $editedAtCode, $editedAtValue);
    }
}
