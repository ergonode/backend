<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\Channel\Infrastructure\Service\DirectoryCompressorInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FileExistsException;
use Ergonode\ExporterFile\Domain\Command\Export\EndFileExportCommand;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Entity\Export;

class EndProcessCommandHandler
{
    private ExportRepositoryInterface $repository;
    private DirectoryCompressorInterface $compressor;

    private TempFileStorage $storage;

    /**
     * @var FilesystemInterface;
     */
    private FilesystemInterface $exportStorage;

    public function __construct(
        ExportRepositoryInterface $repository,
        DirectoryCompressorInterface $compressor,
        TempFileStorage $storage,
        FilesystemInterface $exportStorage
    ) {
        $this->repository = $repository;
        $this->compressor = $compressor;
        $this->storage = $storage;
        $this->exportStorage = $exportStorage;
    }

    /**
     * @throws FileExistsException
     */
    public function __invoke(EndFileExportCommand $command): void
    {
        $export  = $this->repository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);

        $fileName = $command->getExportId()->getValue();
        $sourceDirectory = sprintf('%s/%s', $this->storage->getDirectory(), $fileName);

        $destinationDirectory = $this->storage->getDirectory();

        $destinationFile = $this->compressor->compress($sourceDirectory, $destinationDirectory, $fileName);
        $compressedFile = sprintf('%s/%s', $destinationDirectory, $destinationFile);
        $handler = fopen($compressedFile, 'rb');
        $this->exportStorage->writeStream($destinationFile, $handler);
        $this->storage->clean($fileName);
        $this->storage->clean($destinationFile);

        $export->end();
        $this->repository->save($export);
    }
}
