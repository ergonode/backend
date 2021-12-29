<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Validator;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ergonode\Multimedia\Domain\Query\MultimediaTypeQueryInterface;

class MultimediaTypeValidator extends ConstraintValidator
{
    private MultimediaTypeQueryInterface $query;

    public function __construct(MultimediaTypeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaType) {
            throw new UnexpectedTypeException($constraint, MultimediaType::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (MultimediaId::isValid($value)) {
            $type = $this->query->findMultimediaType(new MultimediaId($value));


            if ($type && $type !== $constraint->type) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ type }}', (string) $constraint->type)
                    ->addViolation();
            }
        }
    }
}
