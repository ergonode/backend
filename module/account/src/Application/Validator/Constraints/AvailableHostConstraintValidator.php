<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AvailableHostConstraintValidator extends ConstraintValidator
{
    private array $sites;

    public function __construct(array $sites)
    {
        $this->sites = $sites;
    }
    /**
     * @param mixed                        $value
     * @param AvailableHostConstraint|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AvailableHostConstraint) {
            throw new UnexpectedTypeException($constraint, AvailableHostConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $hostname = parse_url($value, PHP_URL_HOST);

        $isset = false;
        foreach ($this->sites as $site) {
            if ($hostname === $site) {
                $isset = true;
                break;
            }
        }

        if (!$isset) {
            $this->context->buildViolation($constraint->validMessage)
                ->setParameter('{{ site }}', $constraint->site)
                ->addViolation();
        }
    }
}
