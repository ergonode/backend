<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\UpdateExportProfileCommandBuilderInterface;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Ergonode\ExporterMagento2\Domain\Command\UpdateMagento2ExportProfileCommand;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;

/**
 */
class Magento2UpdateExportProfileCommandBuilder implements UpdateExportProfileCommandBuilderInterface
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
     * @param ExportProfileId $exportProfileId
     * @param FormInterface   $form
     *
     * @return DomainCommandInterface
     */
    public function build(ExportProfileId $exportProfileId, FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterMagento2CsvConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $filename = $data->filename;
        $language = $data->defaultLanguage;

        return new UpdateMagento2ExportProfileCommand(
            $exportProfileId,
            $name,
            $filename,
            $language
        );
    }
}
