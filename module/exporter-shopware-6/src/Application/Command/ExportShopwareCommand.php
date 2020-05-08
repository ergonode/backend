<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Command;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CategoryTreeMapper;
use Ergonode\ExporterShopware6\Infrastructure\Synchronize\CategorySynchronize;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ExportShopwareCommand extends Command
{
    protected static $defaultName = 'test:export:shopware';
//
//    /**
//     * @var Shopware6Connector
//     */
//    private Shopware6Connector $connector;

    /**
     * @var CategoryTreeMapper
     */
    private CategoryTreeMapper $mapper;

    /**
     * @var CategorySynchronize
     */
    private CategorySynchronize $synchronize;

    private ExportProfileRepositoryInterface $repository;

    /**
     * @param CategoryTreeMapper               $mapper
     * @param CategorySynchronize              $synchronize
     * @param ExportProfileRepositoryInterface $repository
     */
    public function __construct(
        CategoryTreeMapper $mapper,
        CategorySynchronize $synchronize,
        ExportProfileRepositoryInterface $repository
    ) {
        parent::__construct();
        $this->mapper = $mapper;
        $this->synchronize = $synchronize;
        $this->repository = $repository;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exportProfileId = new ExportProfileId('7e5d4171-fff2-4d98-83d8-7c231ef8b1f6');
        $treeId = Uuid::fromString('d14d03c7-b270-4b9c-83ae-e79a055a7a46');
        $exportProfile = $this->repository->load($exportProfileId);

        $this->categoryTreeMapper($exportProfile, $treeId);
        $this->categoryTreeSynchronize($exportProfile, $treeId);
    }

    /**
     * @param AbstractExportProfile $exportProfile
     * @param Uuid                  $treeId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function categoryTreeMapper(AbstractExportProfile $exportProfile, Uuid $treeId)
    {

        $this->mapper->map(
            $exportProfile,
            $treeId
        );
    }

    /**
     * @param AbstractExportProfile $exportProfile
     * @param Uuid                  $treeId
     */
    private function categoryTreeSynchronize(AbstractExportProfile $exportProfile, Uuid $treeId)
    {
        $this->synchronize->synchronize($exportProfile, $treeId);
    }
}
