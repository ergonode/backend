<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductExistsValidator extends ConstraintValidator
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductExists) {
            throw new UnexpectedTypeException($constraint, ProductExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!ProductId::isValid($value)) {
            return;
        }

        $result = $this->repository->exists(new ProductId($value));

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
