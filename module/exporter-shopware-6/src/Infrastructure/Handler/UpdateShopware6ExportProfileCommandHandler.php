<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;

/**
 */
class UpdateShopware6ExportProfileCommandHandler
{
    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $repository;

    /**
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(ExportProfileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateShopware6ExportProfileCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(UpdateShopware6ExportProfileCommand $command)
    {
        /** @var Shopware6ExportApiProfile $exportProfile */
        $exportProfile = $this->repository->load($command->getId());
        $exportProfile->setName($command->getName());
        $exportProfile->setHost($command->getHost());
        $exportProfile->setClientId($command->getClientId());
        $exportProfile->setClientKey($command->getClientKey());
        $exportProfile->setDefaultLanguage($command->getDefaultLanguage());
        $exportProfile->setProductName($command->getProductName());
        $exportProfile->setProductActive($command->getProductActive());
        $exportProfile->setProductStock($command->getProductStock());
        $exportProfile->setProductPrice($command->getProductPrice());
        $exportProfile->setProductTax($command->getProductTax());
        $exportProfile->setCategoryTree($command->getCategoryTree());
        $exportProfile->setAttributes($command->getAttributes());

        $this->repository->save($exportProfile);
    }
}
