<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportChannelCommand;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class CreateFileExportChannelCommandBuilder implements CreateChannelCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return FileExportChannel::TYPE === $type;
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

        return new CreateFileExportChannelCommand(
            ChannelId::generate(),
            $name,
            $format,
        );
    }
}
