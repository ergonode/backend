<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Domain\Builder;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Ergonode\ExporterMagento2\Application\Form\Model\ExporterMagento2CsvConfigurationModel;
use Symfony\Component\Form\FormInterface;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportChannelCommand;

class Magento2CreateExportChannelCommandBuilder implements CreateChannelCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return Magento2CsvChannel::TYPE === $type;
    }

    /**
     * @throws \Exception
     */
    public function build(FormInterface $form): CreateChannelCommandInterface
    {
        /** @var ExporterMagento2CsvConfigurationModel $data */
        $data = $form->getData();

        $name = $data->name;
        $filename = $data->filename;
        $language = $data->defaultLanguage;

        return new CreateMagento2ExportChannelCommand(
            ChannelId::generate(),
            $name,
            $filename,
            $language
        );
    }
}
