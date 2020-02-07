<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeCodeUnique;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class ProductCollectionTypeCodeUniqueValidator extends ConstraintValidator
{
    /**
     * @var ProductCollectionTypeRepositoryInterface
     */
    private ProductCollectionTypeRepositoryInterface $repository;

    /**
     * @param ProductCollectionTypeRepositoryInterface $repository
     */
    public function __construct(ProductCollectionTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                                      $value
     * @param ProductCollectionTypeCodeUnique|Constraint $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductCollectionTypeCodeUnique) {
            throw new UnexpectedTypeException($constraint, ProductCollectionTypeCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $code = new ProductCollectionTypeCode($value);
        $collection = $this->repository->exists(ProductCollectionTypeId::fromCode($code));

        if ($collection) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
