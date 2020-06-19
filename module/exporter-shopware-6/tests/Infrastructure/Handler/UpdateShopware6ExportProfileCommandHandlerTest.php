<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Handler\UpdateShopware6ExportProfileCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateShopware6ExportProfileCommandHandlerTest extends TestCase
{
    /**
     */
    public function testHandling():void
    {
        $profile = $this->createMock(Shopware6ExportApiProfile::class);
        $profile->expects($this->once())->method('setName');
        $profile->expects($this->once())->method('setHost');
        $profile->expects($this->once())->method('setClientId');
        $profile->expects($this->once())->method('setClientKey');
        $profile->expects($this->once())->method('setDefaultLanguage');
        $profile->expects($this->once())->method('setProductName');
        $profile->expects($this->once())->method('setProductActive');
        $profile->expects($this->once())->method('setProductStock');
        $profile->expects($this->once())->method('setProductPrice');
        $profile->expects($this->once())->method('setProductTax');
        $profile->expects($this->once())->method('setAttributes');

        $command = $this->createMock(UpdateShopware6ExportProfileCommand::class);
        $repository = $this->createMock(ExportProfileRepositoryInterface::class);
        $repository->expects($this->once())->method('load')->willReturn($profile);
        $repository->expects($this->once())->method('save');

        $handler = new UpdateShopware6ExportProfileCommandHandler($repository);
        $handler->__invoke($command);
    }
}
