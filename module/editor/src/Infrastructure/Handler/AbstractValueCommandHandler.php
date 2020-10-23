<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractCollectionAttribute;

abstract class AbstractValueCommandHandler
{
    /**
     * @param mixed $value
     */
    protected function createValue(Language $language, AbstractAttribute $attribute, $value = null): ?ValueInterface
    {
        if ($attribute instanceof MultiSelectAttribute) {
            if (empty($value)) {
                return null;
            }

            return new StringCollectionValue([$language->getCode() => implode(',', $value)]);
        }
        if ($attribute instanceof AbstractCollectionAttribute) {
            if (empty($value)) {
                $value = [];
            }

            return new StringCollectionValue([$language->getCode() => implode(',', $value)]);
        }

        if ($attribute instanceof SelectAttribute) {
            return new TranslatableStringValue(new TranslatableString([$language->getCode() => (string) $value]));
        }

        if ($attribute->isMultilingual()) {
            return new TranslatableStringValue(
                new TranslatableString(
                    [$language->getCode() => !is_null($value) ? (string) $value : null]
                )
            );
        }

        return new StringValue((string) $value);
    }

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
