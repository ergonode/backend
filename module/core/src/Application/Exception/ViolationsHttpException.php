<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 */
class ViolationsHttpException extends HttpException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;

    /**
     * @param ConstraintViolationListInterface $violations
     * @param \Exception|null                  $previous
     * @param array                            $headers
     */
    public function __construct(ConstraintViolationListInterface $violations, \Exception $previous = null, array $headers = [])
    {
        $this->violations = $violations;

        parent::__construct(400, 'Validation error', $previous);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
