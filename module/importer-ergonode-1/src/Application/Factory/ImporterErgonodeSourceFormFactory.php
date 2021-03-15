<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Application\Factory;

use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\ImporterErgonode1\Application\Form\ImporterErgonodeConfigurationForm;
use Ergonode\ImporterErgonode1\Application\Model\ImporterErgonodeConfigurationModel;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ImporterErgonodeSourceFormFactory implements SourceFormFactoryInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supported(string $type): bool
    {
        return ErgonodeZipSource::TYPE === $type;
    }

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
