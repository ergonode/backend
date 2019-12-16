<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\ExportProductCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Infrastructure\Service\ChannelExportService;
use Ergonode\Channel\Infrastructure\Service\ChannelValidationService;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class ExportProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var ChannelValidationService
     */
    private $validationService;

    /**
     * @var ChannelExportService
     */
    private $exportService;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param ChannelValidationService   $validationService
     * @param ChannelExportService       $exportService
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ChannelRepositoryInterface $channelRepository,
        ChannelValidationService $validationService,
        ChannelExportService $exportService
    ) {
        $this->productRepository = $productRepository;
        $this->channelRepository = $channelRepository;
        $this->validationService = $validationService;
        $this->exportService = $exportService;
    }

    /**
     * @param ExportProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ExportProductCommand $command): void
    {
        $domainProduct = $this->productRepository->load($command->getProductId());
        $channel = $this->channelRepository->load($command->getChannelId());

        Assert::notNull($channel);
        Assert::notNull($domainProduct);

        if ($this->validationService->isValid($domainProduct, $channel)) {
            $this->exportService->process($channel, $domainProduct);
        }
    }
}
