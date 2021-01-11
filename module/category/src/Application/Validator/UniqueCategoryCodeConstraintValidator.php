<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Validator;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueCategoryCodeConstraintValidator extends ConstraintValidator
{
    private CategoryQueryInterface $query;

    public function __construct(CategoryQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                                   $value
     * @param UniqueCategoryCodeConstraint|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueCategoryCodeConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueCategoryCodeConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        if (!CategoryCode::isValid($value)) {
            return;
        }

        $categoryId = $this->query->findIdByCode(new CategoryCode($value));
        if ($categoryId) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
