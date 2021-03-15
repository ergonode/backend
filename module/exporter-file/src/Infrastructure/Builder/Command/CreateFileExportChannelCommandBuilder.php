<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportChannelCommand;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;

class CreateFileExportChannelCommandBuilder implements CreateChannelCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return FileExportChannel::TYPE === $type;
    }

    /**
     * @throws \Exception
     */
    public function build(FormInterface $form): CreateChannelCommandInterface
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

        return new CreateFileExportChannelCommand(
            ChannelId::generate(),
            $name,
            $format,
            $exportType,
            $languages
        );
    }
}
