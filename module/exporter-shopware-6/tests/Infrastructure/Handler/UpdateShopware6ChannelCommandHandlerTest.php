<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler;

use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Infrastructure\Handler\UpdateShopware6ChannelCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

class UpdateShopware6ChannelCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $channel = $this->createMock(Shopware6Channel::class);
        $channel->expects(self::once())->method('setHost');
        $channel->expects(self::once())->method('setClientId');
        $channel->expects(self::once())->method('setClientKey');
        $channel->expects(self::once())->method('setDefaultLanguage');
        $channel->expects(self::once())->method('setLanguages');
        $channel->expects(self::once())->method('setAttributeProductName');
        $channel->expects(self::once())->method('setAttributeProductActive');
        $channel->expects(self::once())->method('setAttributeProductStock');
        $channel->expects(self::once())->method('setAttributeProductPriceGross');
        $channel->expects(self::once())->method('setAttributeProductPriceNet');
        $channel->expects(self::once())->method('setAttributeProductTax');
        $channel->expects(self::once())->method('setPropertyGroup');
        $channel->expects(self::once())->method('setCustomField');

        $command = $this->createMock(UpdateShopware6ChannelCommand::class);
        $repository = $this->createMock(ChannelRepositoryInterface::class);
        $repository->expects(self::once())->method('load')->willReturn($channel);
        $repository->expects(self::once())->method('save');

        $handler = new UpdateShopware6ChannelCommandHandler($repository);
        $handler->__invoke($command);
    }
}
