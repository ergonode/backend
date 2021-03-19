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

        if (null === $value->skus) {
            return;
        }
        /** @var  $variableProduct VariableProduct */
        $variableProduct = $value->parentProduct;
        $bindings = $variableProduct->getBindings();
        $bindingsValues = [];
        foreach ($bindings as $binding) {
            $bindingsValues[] = $binding->getValue();
        }
        foreach ($value->skus as $index => $sku) {
            $attributeIds = $this->query->findAttributeIdsBySku($sku);
            if (empty(array_intersect($bindingsValues, $attributeIds))) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ sku }}', $sku)
                    ->atPath('skus['.$index.']')
                    ->addViolation() ;
            }
        }
    }
}
