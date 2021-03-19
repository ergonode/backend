<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Product\Application\Model\Product\Relation\ProductChildFormModel;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Webmozart\Assert\Assert;

class ProductInvalidChildValidator extends ConstraintValidator
{
    private ProductQueryInterface $query;
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductQueryInterface $query, ProductRepositoryInterface $productRepository)
    {
        $this->query = $query;
        $this->productRepository = $productRepository;
    }


    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductInvalidChild) {
            throw new UnexpectedTypeException($constraint, ProductInvalidChild::class);
        }

        if (!$value instanceof ProductChildFormModel) {
            throw new UnexpectedTypeException($value, ProductChildFormModel::class);
        }

        if (!$value->getParentId() instanceof ProductId) {
            throw new UnexpectedTypeException($value->getParentId(), VariableProduct::class);
        }

        if (null === $value->childId || !Uuid::isValid($value->childId)) {
            return;
        }
        /** @var  $variableProduct VariableProduct */
        $variableProductId = $value->getParentId();
        $variableProduct = $this->productRepository->load($variableProductId);

        Assert::isInstanceOf($variableProduct, VariableProduct::class);

        $bindings = $variableProduct->getBindings();
        $bindingsValues = [];
        foreach ($bindings as $binding) {
            $bindingsValues[] = $binding->getValue();
        }
        $attributeIds = $this->query->findAttributeIdsByProductId($value->childId);
        if (empty(array_intersect($bindingsValues, $attributeIds))) {
            $this->context->buildViolation($constraint->message)
                ->atPath('childId')
                ->addViolation();
        }
    }
}
