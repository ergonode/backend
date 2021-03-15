<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Validator;

use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductCollectionTypeCodeUniqueValidator extends ConstraintValidator
{
    private ProductCollectionTypeQueryInterface $query;

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
        if (!ProductCollectionTypeCode::isValid($value)) {
            return;
        }

        $code = new ProductCollectionTypeCode($value);
        $collectionTypeId = $this->query->findIdByCode($code);

        if ($collectionTypeId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
