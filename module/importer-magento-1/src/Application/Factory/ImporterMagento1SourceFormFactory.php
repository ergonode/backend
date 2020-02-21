<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\ImporterMagento1\Application\Form\ImporterMagento1ConfigurationForm;
use Symfony\Component\Form\FormInterface;
use Ergonode\ImporterMagento1\Application\Model\ImporterMagento1ConfigurationModel;
use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;

/**
 */
class ImporterMagento1SourceFormFactory implements SourceFormFactoryInterface
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
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return Magento1CsvSource::TYPE === $type;
    }

    /**
     * @param AbstractSource|null $source
     *
     * @return FormInterface
     */
    public function create(AbstractSource $source = null): FormInterface
    {
        $model = new ImporterMagento1ConfigurationModel($source);

        return $this->formFactory->create(ImporterMagento1ConfigurationForm::class, $model);
    }
}
