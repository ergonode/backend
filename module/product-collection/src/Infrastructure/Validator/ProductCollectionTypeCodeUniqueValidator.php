<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\ProductCollection\Infrastructure\Validator\Constraints\ProductCollectionTypeCodeUnique;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;

/**
 */
class ProductCollectionTypeCodeUniqueValidator extends ConstraintValidator
{
    /**
     * @var ProductCollectionTypeQueryInterface
     */
    private ProductCollectionTypeQueryInterface $query;

    /**
     * @param ProductCollectionTypeQueryInterface $query
     */
    public function __construct(ProductCollectionTypeQueryInterface $query)
    {
        $this->query = $query;
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
        $collectionTypeId = $this->query->findIdByCode($code);

        if ($collectionTypeId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
