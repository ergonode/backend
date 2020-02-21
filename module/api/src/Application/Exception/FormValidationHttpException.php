<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 */
class FormValidationHttpException extends HttpException
{
    /**
     * @var FormInterface
     */
    private FormInterface $form;

    /**
     * @param FormInterface $violations
     */
    public function __construct(FormInterface $violations)
    {
        $this->form = $violations;

        parent::__construct(Response::HTTP_BAD_REQUEST, 'Validation error');
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
