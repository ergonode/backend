<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CategoryTreeMapper;
use Ergonode\ExporterShopware6\Infrastructure\Synchronize\CategorySynchronize;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
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
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * ExportShopwareCommand constructor.
     *
     * @param CategoryTreeMapper               $mapper
     * @param CategorySynchronize              $synchronize
     * @param ExportProfileRepositoryInterface $repository
     * @param CommandBusInterface              $commandBus
     */
    public function __construct(
        CategoryTreeMapper $mapper,
        CategorySynchronize $synchronize,
        ExportProfileRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        parent::__construct();
        $this->mapper = $mapper;
        $this->synchronize = $synchronize;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
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
        $exportProfileId = $this->createExportProfile();

        $treeId = $this->getTree();
        $exportProfile = $this->repository->load($exportProfileId);

        $this->categoryTreeMapper($exportProfile, $treeId);
        $this->categoryTreeSynchronize($exportProfile, $treeId);
    }

    /**
     * @return ExportProfileId
     *
     * @throws \Exception
     */
    private function createExportProfile()
    {
        $command = new CreateShopware6ExportProfileCommand(
            ExportProfileId::generate(),
            'TEST WF',
            'http://192.168.55.98:8000',
            'SWIAMURTYTK0R2RQEFBVUNPDTQ',
            'Mml6ZkJoRVdGSlZhbDNwMjZEcDFRMUQ0a1JRNUJKWDFKMWNnV08',
            Language::fromString('en'),
            $this->getNameAttribute(),
            $this->getActive(),
            $this->getStock(),
            $this->getPrice(),
            $this->getTax(),
            []
        );

        $this->commandBus->dispatch($command);

        return $command->getId();
    }

    /**
     * @return AttributeId
     */
    private function getNameAttribute(): AttributeId
    {
        return AttributeId::fromKey('code_1');
    }

    /**
     * @return AttributeId
     */
    private function getActive(): AttributeId
    {
        return AttributeId::fromKey('code_2');
    }

    /**
     * @return AttributeId
     */
    private function getStock(): AttributeId
    {
        return AttributeId::fromKey('code_21');
    }

    /**
     * @return AttributeId
     */
    private function getPrice(): AttributeId
    {
        return AttributeId::fromKey('code_31');
    }

    /**
     * @return AttributeId
     */
    private function getTax(): AttributeId
    {
        return AttributeId::fromKey('code_22');
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    private function getTree()
    {
        return  Uuid::fromString('853d54e8-4540-5f12-8655-7f804f43e1f8');
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
