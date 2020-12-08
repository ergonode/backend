<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AvailableTokenConstraintValidator extends ConstraintValidator
{
    private UserResetPasswordTokenRepositoryInterface $repository;

    public function __construct(UserResetPasswordTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                               $value
     * @param Constraint|AvailableTokenConstraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AvailableTokenConstraint) {
            throw new UnexpectedTypeException($constraint, AvailableTokenConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!ResetToken::isValid($value)) {
            $this->context->buildViolation($constraint->validMessage)
                ->addViolation();

            return;
        }

        $userToken = $this->repository->load(new ResetToken($value));
        if (!$userToken) {
            $this->context->buildViolation($constraint->validMessage)
                ->addViolation();

            return;
        }
        $now = new \DateTime();

        if (null !== $userToken->getConsumed()
            || $userToken->getExpiresAt() <= $now
        ) {
            $this->context->buildViolation($constraint->validMessage)
                ->addViolation();

            return;
        }
    }
}
