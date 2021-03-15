<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Domain\Builder;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Symfony\Component\Form\FormInterface;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\ExporterMagento2\Domain\Command\UpdateMagento2ExportChannelCommand;

class Magento2UpdateExportChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return Magento2CsvChannel::TYPE === $type;
    }

    public function build(ChannelId $channelId, FormInterface $form): ChannelCommandInterface
    {
        /** @var ExporterMagento2CsvConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $filename = $data->filename;
        $language = $data->defaultLanguage;

        return new UpdateMagento2ExportChannelCommand(
            $channelId,
            $name,
            $filename,
            $language
        );
    }
}
