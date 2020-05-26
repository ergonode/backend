<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Application\Factory;

use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Application\Form\ExporterFileConfigurationForm;

/**
 */
class ExportFileProfileFormFactory implements ExportProfileFormFactoryInterface
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
        return FileExportProfile::TYPE === $type;
    }

    /**
     * @param AbstractExportProfile|FileExportProfile|null $exportProfile
     *
     * @return FormInterface
     */
    public function create(AbstractExportProfile $exportProfile = null): FormInterface
    {
        Assert::nullOrIsInstanceOf($exportProfile, FileExportProfile::class);
        $model = new ExporterFileConfigurationModel($exportProfile);
        $method = $exportProfile ? Request::METHOD_PUT : Request::METHOD_POST;

        return $this->formFactory->create(ExporterFileConfigurationForm::class, $model, ['method' => $method]);
    }
}
