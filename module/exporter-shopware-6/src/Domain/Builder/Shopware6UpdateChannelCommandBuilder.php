<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\ExporterShopware6\Application\Form\Model\ChannelShopware6ConfigurationModel;
use Ergonode\ExporterShopware6\Domain\Command\Channel\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Symfony\Component\Form\FormInterface;

/**
 */
class Shopware6UpdateChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return Shopware6ExportApiProfile::TYPE === $type;
    }

    /**
     * @param ChannelId     $channelId
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function build(ChannelId $channelId, FormInterface $form): DomainCommandInterface
    {
        /** @var ChannelShopware6ConfigurationModel $data */
        $data = $form->getData();

        $treeId = $data->categoryTreeId;

        return new UpdateShopware6ChannelCommand(
            $channelId,
            $treeId
        );
    }
}
