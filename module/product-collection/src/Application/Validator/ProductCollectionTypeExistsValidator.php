<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProductCollectionTypeExistsValidator extends ConstraintValidator
{
    private ProductCollectionTypeRepositoryInterface $repository;

    public function __construct(ProductCollectionTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                                  $value
     * @param ProductCollectionTypeExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductCollectionTypeExists) {
            throw new UnexpectedTypeException($constraint, ProductCollectionTypeExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $result = false;
        if (ProductCollectionTypeId::isValid($value)) {
            $result = $this->repository->exists(new ProductCollectionTypeId($value));
        }

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
