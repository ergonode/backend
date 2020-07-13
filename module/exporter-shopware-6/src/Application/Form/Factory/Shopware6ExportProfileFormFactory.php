<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Form\Factory;

use Ergonode\Exporter\Application\Provider\ExportProfileFormFactoryInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterShopware6\Application\Form\ExporterShopware6ConfigurationForm;
use Ergonode\ExporterShopware6\Application\Form\Model\ExporterShopware6ConfigurationModel;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class Shopware6ExportProfileFormFactory implements ExportProfileFormFactoryInterface
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
        return Shopware6ExportApiProfile::TYPE === $type;
    }

    /**
     * @param AbstractExportProfile|null $exportProfile
     *
     * @return FormInterface
     */
    public function create(AbstractExportProfile $exportProfile = null): FormInterface
    {
        $model = new ExporterShopware6ConfigurationModel($exportProfile);
        if ($exportProfile) {
            return $this->formFactory->create(
                ExporterShopware6ConfigurationForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
        }

        return $this->formFactory->create(ExporterShopware6ConfigurationForm::class, $model);
    }
}
