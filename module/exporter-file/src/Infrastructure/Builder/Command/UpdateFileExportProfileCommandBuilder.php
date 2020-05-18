<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\UpdateExportProfileCommandBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportProfileCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;

/**
 */
class UpdateFileExportProfileCommandBuilder implements UpdateExportProfileCommandBuilderInterface
{
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
     * @param ExportProfileId $exportProfileId
     * @param FormInterface   $form
     *
     * @return DomainCommandInterface
     */
    public function build(ExportProfileId $exportProfileId, FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterFileConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;

        return new UpdateFileExportProfileCommand(
            $exportProfileId,
            $name
        );
    }
}
