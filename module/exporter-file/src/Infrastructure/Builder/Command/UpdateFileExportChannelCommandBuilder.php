<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;

class UpdateFileExportChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return FileExportChannel::TYPE === $type;
    }

    public function build(ChannelId $channelId, FormInterface $form): ChannelCommandInterface
    {
        /** @var ExporterFileConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $format = $data->format;
        $exportType = $data->exportType;

        $languages = [];
        foreach ($data->languages as $language) {
            $languages[] = new Language($language);
        }

        return new UpdateFileExportChannelCommand(
            $channelId,
            $name,
            $format,
            $exportType,
            $languages,
        );
    }
}
