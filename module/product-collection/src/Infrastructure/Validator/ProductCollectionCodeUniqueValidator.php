<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionCodeUnique;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProductCollectionCodeUniqueValidator extends ConstraintValidator
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $repository;

    /**
     * @param ProductCollectionRepositoryInterface $repository
     */
    public function __construct(ProductCollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                                  $value
     * @param ProductCollectionCodeUnique|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductCollectionCodeUnique) {
            throw new UnexpectedTypeException($constraint, ProductCollectionCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $code = new ProductCollectionCode($value);
        $collection = $this->repository->exists(ProductCollectionId::fromCode($code->getValue()));

        if ($collection) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
