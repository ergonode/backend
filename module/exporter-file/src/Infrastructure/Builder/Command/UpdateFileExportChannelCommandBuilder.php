<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class UpdateFileExportChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
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
     * @param ChannelId     $channelId
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function build(ChannelId $channelId, FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterFileConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $format = $data->format;

        return new UpdateFileExportChannelCommand(
            $channelId,
            $name,
            $format,
        );
    }
}
