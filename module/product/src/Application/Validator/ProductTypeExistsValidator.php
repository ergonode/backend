<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Product\Application\Provider\ProductSupportProviderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class ProductTypeExistsValidator extends ConstraintValidator
{
    private ProductSupportProviderInterface $productSupportProvider;

    public function __construct(ProductSupportProviderInterface $productSupportProvider)
    {
        $this->productSupportProvider = $productSupportProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductTypeExists) {
            throw new UnexpectedTypeException($constraint, ProductTypeExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if ($this->productSupportProvider->supports($value)) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
