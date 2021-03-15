<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Application\Form\Factory;

use Ergonode\ExporterMagento2\Application\Form\ExporterMagento2ConfigurationForm;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

class Magento2ExportCsvChannelFormFactory implements ChannelFormFactoryInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supported(string $type): bool
    {
        return Magento2CsvChannel::TYPE === $type;
    }

    public function create(AbstractChannel $channel = null): FormInterface
    {
        $model = new ExporterMagento2CsvConfigurationModel($channel);
        if ($channel) {
            return $this->formFactory->create(
                ExporterMagento2ConfigurationForm::class,
                $model,
                ['method' => Request::METHOD_PUT]
            );
        }

        return $this->formFactory->create(ExporterMagento2ConfigurationForm::class, $model);
    }
}
