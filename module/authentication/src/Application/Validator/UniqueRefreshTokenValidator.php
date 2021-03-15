<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Validator;

use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueRefreshTokenValidator extends ConstraintValidator
{
    private RefreshTokenRepositoryInterface $repository;

    public function __construct(RefreshTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof RefreshToken) {
            throw new UnexpectedTypeException($value, RefreshToken::class);
        }
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }

        if (!$this->repository->findOneBy(['refreshToken' => $value->getRefreshToken()])) {
            return;
        }

        $this->context
            ->buildViolation('Refresh token already exists')
            ->addViolation();
    }
}
