<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 */
class DataValidationHttpException extends HttpException
{
    /**
     * @var array
     */
    private $messages;

    /**
     * @param array $messages
     */
    public function __construct(array $messages = [])
    {
        $this->messages = $messages;

        parent::__construct(Response::HTTP_BAD_REQUEST, 'Validation error');
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
