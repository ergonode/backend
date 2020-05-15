<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Application\Form\Factory;

use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterMagento2\Application\Form\ExporterMagento2ConfigurationForm;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class Magento2ExportCsvProfileFormFactory implements ExportProfileFormFactoryInterface
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
        return Magento2ExportCsvProfile::TYPE === $type;
    }

    /**
     * @param AbstractExportProfile|null $exportProfile
     *
     * @return FormInterface
     */
    public function create(AbstractExportProfile $exportProfile = null): FormInterface
    {
        $model = new ExporterMagento2CsvConfigurationModel($exportProfile);
        if ($exportProfile) {
            return $this->formFactory->create(
                ExporterMagento2ConfigurationForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
        }

        return $this->formFactory->create(ExporterMagento2ConfigurationForm::class, $model);
    }
}
