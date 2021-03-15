<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RoleExistsValidator extends ConstraintValidator
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param mixed                 $value
     * @param RoleExists|Constraint $constraint
     *
     * @throws \ReflectionException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof RoleExists) {
            throw new UnexpectedTypeException($constraint, RoleExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $attribute = false;
        if (RoleId::isValid($value)) {
            $attribute = $this->roleRepository->load(new RoleId($value));
        }

        if (!$attribute) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
