<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Webmozart\Assert\Assert;

class HostAvailableValidator extends ConstraintValidator
{
    private array $hosts;

    public function __construct(array $hosts)
    {
        Assert::allString($hosts);
        $this->hosts = $hosts;
    }

    /**
     * @param mixed                              $value
     * @param HostAvailable|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof HostAvailable) {
            throw new UnexpectedTypeException($constraint, HostAvailable::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $hostname = parse_url($value, PHP_URL_HOST);

        $isset = false;
        foreach ($this->hosts as $host) {
            $siteHost = parse_url($value, PHP_URL_HOST);
            if (!$siteHost) {
                $siteHost = $host;
            }
            if ($hostname === $siteHost) {
                $isset = true;
                break;
            }
        }

        if (!$isset) {
            $this->context->buildViolation($constraint->validMessage)
                ->addViolation();
        }
    }
}
