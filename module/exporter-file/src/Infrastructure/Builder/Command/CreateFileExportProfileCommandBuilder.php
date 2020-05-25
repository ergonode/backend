<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\CreateExportProfileCommandBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportProfileCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;

/**
 */
class CreateFileExportProfileCommandBuilder implements CreateExportProfileCommandBuilderInterface
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
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     *
     * @throws \Exception
     */
    public function build(FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterFileConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $format = $data->format;

        return new CreateFileExportProfileCommand(
            ExportProfileId::generate(),
            $name,
            $format,
        );
    }
}
