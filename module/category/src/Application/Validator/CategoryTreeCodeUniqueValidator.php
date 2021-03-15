<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Category\Domain\Query\TreeQueryInterface;

class CategoryTreeCodeUniqueValidator extends ConstraintValidator
{
    private TreeQueryInterface $query;

    public function __construct(TreeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                         $value
     * @param CategoryTreeExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CategoryTreeCodeUnique) {
            throw new UnexpectedTypeException($constraint, CategoryTreeCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }



        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }



        $value = (string) $value;

        $treeId = $this->query->findTreeIdByCode($value);

        if ($treeId) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
