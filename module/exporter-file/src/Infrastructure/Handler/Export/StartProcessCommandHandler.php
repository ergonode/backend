<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportHeaderBuilderInterface;
use Webmozart\Assert\Assert;

class StartProcessCommandHandler
{
    private ExportRepositoryInterface $repository;

    private TempFileStorage $storage;

    /**
     * @var ExportHeaderBuilderInterface[]
     */
    private iterable $builders;

    public function __construct(
        ExportRepositoryInterface $repository,
        TempFileStorage $storage,
        iterable $builders
    ) {
        $this->repository = $repository;
        $this->storage = $storage;

        Assert::allIsInstanceOf($builders, ExportHeaderBuilderInterface::class);
        $this->builders = $builders;
    }

    public function __invoke(StartFileExportCommand $command): void
    {
        $export = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $export->start();
        $this->repository->save($export);


        foreach ($this->builders as $builder) {
            $this->storage->create(sprintf('%s/%s.csv', $command->getExportId()->getValue(), $builder->filename()));
            $this->storage->append([implode(',', $builder->header()).PHP_EOL]);
            $this->storage->close();
        }
    }
}
