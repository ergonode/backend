<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\ImporterMagento1\Application\Form\ImporterMagento1ConfigurationForm;
use Symfony\Component\Form\FormInterface;
use Ergonode\ImporterMagento1\Application\Model\ImporterMagento1ConfigurationModel;

/**
 */
class ImporterMagento1ConfigurationFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string|null $type
     *
     * @return FormInterface
     */
    public function create(string $type = null): FormInterface
    {
        return $this->formFactory->create(ImporterMagento1ConfigurationForm::class);
    }
}
