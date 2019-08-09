<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 */
class FormValidationHttpException extends HttpException
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @param FormInterface   $form
     * @param \Exception|null $previous
     * @param array           $headers
     */
    public function __construct(FormInterface $form, \Exception $previous = null, array $headers = [])
    {
        $this->form = $form;

        parent::__construct(400, 'Validation error', $previous);
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
