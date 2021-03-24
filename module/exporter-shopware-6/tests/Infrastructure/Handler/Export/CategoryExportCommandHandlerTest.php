<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Handler\Export;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Handler\Export\CategoryExportCommandHandler;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\CategoryShopware6ExportProcess;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryExportCommandHandlerTest extends TestCase
{
    /**
     * @var ExportRepositoryInterface|MockObject
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface|MockObject
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var CategoryShopware6ExportProcess|MockObject
     */
    private CategoryShopware6ExportProcess $process;

    protected function setUp(): void
    {
        $this->exportRepository = $this->createMock(ExportRepositoryInterface::class);
        $this->exportRepository->method('load')
            ->willReturn($this->createMock(Export::class));
        $this->exportRepository->expects(self::once())->method('load');

        $this->channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $this->channelRepository->method('load')
            ->willReturn($this->createMock(Shopware6Channel::class));
        $this->channelRepository->expects(self::once())->method('load');

        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $this->categoryRepository->method('load')
            ->willReturn($this->createMock(AbstractCategory::class));
        $this->categoryRepository->expects(self::once())->method('load');

        $this->process = $this->createMock(CategoryShopware6ExportProcess::class);
        $this->process->expects(self::once())->method('process');
    }

    public function testHandling(): void
    {
        $command = $this->createMock(CategoryExportCommand::class);

        $handler = new CategoryExportCommandHandler(
            $this->exportRepository,
            $this->channelRepository,
            $this->categoryRepository,
            $this->process
        );
        $handler->__invoke($command);
    }
}
