<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Validator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class SkuNotExistsValidator extends ConstraintValidator
{
    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * SkuExistsValidator constructor.
     *
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                   $value
     * @param SkuNotExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SkuNotExists) {
            throw new UnexpectedTypeException($constraint, SkuNotExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $sku = new \Ergonode\Product\Domain\ValueObject\Sku($value);

        $result = $this->query->findBySku($sku);

        if (empty($result)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
