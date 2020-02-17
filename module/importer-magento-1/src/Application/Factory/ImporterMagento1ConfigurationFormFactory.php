<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\ImporterMagento1\Application\Form\ImporterMagento1ConfigurationForm;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
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
     * @param AbstractSource|Magento1CsvSource|null $source
     *
     * @return FormInterface
     */
    public function create(?AbstractSource $source = null): FormInterface
    {
        $model = new ImporterMagento1ConfigurationModel();

        return $this->formFactory->create(ImporterMagento1ConfigurationForm::class, $model);
    }
}
