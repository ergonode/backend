<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Account\Domain\Entity\User;

/**
 */
abstract class AbstractValueCommandHandler
{
    /**
     * @param Language          $language
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     *
     * @return ValueInterface|null
     */
    protected function createValue(Language $language, AbstractAttribute $attribute, $value = null): ?ValueInterface
    {
        if (null === $value) {
            return null;
        }

        if ($attribute instanceof MultiSelectAttribute) {
            return new StringCollectionValue($value);
        }

        if ($attribute instanceof SelectAttribute) {
            return new TranslatableStringValue(new TranslatableString([$language->getCode() => (string) $value]));
        }

        if ($attribute->isMultilingual()) {
            return new TranslatableStringValue(new TranslatableString([$language->getCode() => (string) $value]));
        }

        return new StringValue((string) $value);
    }

    /**
     * @param ProductDraft   $product
     * @param AttributeCode  $code
     * @param ValueInterface $value
     *
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
     * @param User         $user
     * @param ProductDraft $draft
     *
     * @throws \Exception
     */
    protected function updateAudit(User $user, ProductDraft $draft): void
    {
        $updatedAt = new \DateTime();
        $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
        $editedAtCode = new AttributeCode(EditedAtSystemAttribute::CODE);
        $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
        $editedAtValue = new StringValue($updatedAt->format('Y-m-d H:i:s'));
        $this->attributeUpdate($draft, $editedByCode, $editedByValue);
        $this->attributeUpdate($draft, $editedAtCode, $editedAtValue);
    }
}
