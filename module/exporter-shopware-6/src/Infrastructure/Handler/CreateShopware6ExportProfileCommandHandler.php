<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class CreateShopware6ExportProfileCommandHandler
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
     * @param CreateShopware6ExportProfileCommand $command
     */
    public function __invoke(CreateShopware6ExportProfileCommand $command)
    {
        $exportProfile = new Shopware6ExportApiProfile(
            $command->getId(),
            $command->getName(),
            $command->getHost(),
            $command->getClientId(),
            $command->getClientKey(),
            $command->getDefaultLanguage(),
            $command->getProductName(),
            $command->getProductActive(),
            $command->getProductStock(),
            $command->getProductPrice(),
            $command->getProductTax(),
            $command->getCategoryTree(),
            $command->getAttributes()
        );

        $this->repository->save($exportProfile);
    }
}
