<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Core\Application\Security\Security;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

abstract class AbstractUpdateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected AttributeRepositoryInterface $attributeRepository;

    protected Security $security;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        Security $security
    ) {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->security = $security;
    }

    /**
     * @throws \Exception
     */
    public function updateAudit(AbstractProduct $product): AbstractProduct
    {
        $user = $this->security->getUser();
        if ($user) {
            $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
            $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $this->attributeUpdate($product, $editedByCode, $editedByValue);
        }

        return $product;
    }

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
}
