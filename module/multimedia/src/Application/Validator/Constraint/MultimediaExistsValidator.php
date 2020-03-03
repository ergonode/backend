<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Validator\Constraint;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class MultimediaExistsValidator extends ConstraintValidator
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $multimediaRepository;

    /**
     * @param MultimediaRepositoryInterface $multimediaRepository
     */
    public function __construct(MultimediaRepositoryInterface $multimediaRepository)
    {
        $this->multimediaRepository = $multimediaRepository;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaExists) {
            throw new UnexpectedTypeException($constraint, MultimediaExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $multimedia = null;
        if (MultimediaId::isValid($value)) {
            $multimedia = $this->multimediaRepository->load(new MultimediaId($value));
        }

        if (!$multimedia) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
