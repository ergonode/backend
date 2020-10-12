<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Application\Factory;

use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\ImporterErgonode\Application\Form\ImporterErgonodeConfigurationForm;
use Ergonode\ImporterErgonode\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
final class ImporterErgonodeSourceFormFactory implements SourceFormFactoryInterface
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
        return ErgonodeZipSource::TYPE === $type;
    }

    /**
     * @param AbstractSource|null $source
     *
     * @return FormInterface
     */
    public function create(?AbstractSource $source = null): FormInterface
    {
        $model = new ImporterErgonodeConfigurationModel($source);
        if (null === $source) {
            return $this->formFactory->create(ImporterErgonodeConfigurationForm::class, $model);
        }

        return $this->formFactory->create(
            ImporterErgonodeConfigurationForm::class,
            $model,
            ['method' => Request::METHOD_PUT]
        );
    }
}
