<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Validator;

use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryTreeExistsValidator extends ConstraintValidator
{
    private TreeRepositoryInterface $repository;

    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                         $value
     * @param CategoryTreeExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CategoryTreeExists) {
            throw new UnexpectedTypeException($constraint, CategoryTreeExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }
        $value = (string) $value;

        $result = $this->repository->exists(new CategoryTreeId($value));

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
