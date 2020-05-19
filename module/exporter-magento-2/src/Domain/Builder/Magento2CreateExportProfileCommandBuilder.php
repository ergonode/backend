<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\CreateExportProfileCommandBuilderInterface;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportProfileCommand;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;

/**
 */
class Magento2CreateExportProfileCommandBuilder implements CreateExportProfileCommandBuilderInterface
{
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
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    public function build(FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterMagento2CsvConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $filename = $data->filename;
        $language = $data->defaultLanguage;

        return new CreateMagento2ExportProfileCommand(
            ExportProfileId::generate(),
            $name,
            $filename,
            $language
        );
    }
}
