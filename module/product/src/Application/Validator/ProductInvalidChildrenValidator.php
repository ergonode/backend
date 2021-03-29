<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildBySkusFormModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku as SkuObject;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductInvalidChildrenValidator extends ConstraintValidator
{
    private ProductQueryInterface $query;

    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                             $value
     * @param ProductInvalidChildren|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductInvalidChildren) {
            throw new UnexpectedTypeException($constraint, ProductInvalidChildren::class);
        }

        if (!$value instanceof ProductChildBySkusFormModel) {
            throw new UnexpectedTypeException($value, ProductChildBySkusFormModel::class);
        }

        if (!$value->parentProduct instanceof VariableProduct) {
            throw new UnexpectedTypeException($value->parentProduct, VariableProduct::class);
        }

        if (empty($value->skus)) {
            return;
        }
        /** @var VariableProduct $variableProduct */
        $variableProduct = $value->parentProduct;
        $bindings = $variableProduct->getBindings();
        $attributeIds = [];
        $sku = null;
        $index = 0;

        $bindingsValues = array_map(static function ($attributeId) {
            return $attributeId->getValue();
        }, $bindings);

        foreach ($value->skus as $index => $sku) {
            $attributeIds = $this->query->findAttributeIdsBySku(new SkuObject($sku));
        }

        $attributeIdsValues = array_map(fn ($attributeId) => $attributeId->getValue(), $attributeIds);

        if (empty(array_intersect($bindingsValues, $attributeIdsValues))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ sku }}', $sku)
                ->atPath('skus['.$index.']')
                ->addViolation();
        }
    }
}
