<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\Exporter\Infrastructure\Service\DirectoryCompressorInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FileExistsException;
use Ergonode\ExporterFile\Domain\Command\Export\EndFileExportCommand;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;

/**
 */
class EndProcessCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $repository;
    /**
     * @var DirectoryCompressorInterface
     */
    private DirectoryCompressorInterface $compressor;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @var FilesystemInterface;
     */
    private FilesystemInterface $exportStorage;

    /**
     * @param ExportRepositoryInterface    $repository
     * @param DirectoryCompressorInterface $compressor
     * @param FileStorage                  $storage
     * @param FilesystemInterface          $exportStorage
     */
    public function __construct(
        ExportRepositoryInterface $repository,
        DirectoryCompressorInterface $compressor,
        FileStorage $storage,
        FilesystemInterface $exportStorage
    ) {
        $this->repository = $repository;
        $this->compressor = $compressor;
        $this->storage = $storage;
        $this->exportStorage = $exportStorage;
    }

    /**
     * @param EndFileExportCommand $command
     *
     * @throws FileExistsException
     */
    public function __invoke(EndFileExportCommand $command)
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
