<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Transformer\Domain\Model\Record;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use League\Flysystem\FilesystemInterface;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
class MultimediaImportAction implements ImportActionInterface
{
    public const TYPE = 'MULTIMEDIA';

    public const URL_FIELD = 'url';
    public const NAME_FIELD = 'name';


    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @var DownloaderInterface
     */
    private DownloaderInterface $downloader;

    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $multimediaStorage;

    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $multimediaQuery;

    /**
     * @param MultimediaRepositoryInterface   $repository
     * @param DownloaderInterface             $downloader
     * @param HashCalculationServiceInterface $hashService
     * @param FilesystemInterface             $multimediaStorage
     * @param MultimediaQueryInterface        $multimediaQuery
     */
    public function __construct(
        MultimediaRepositoryInterface $repository,
        DownloaderInterface $downloader,
        HashCalculationServiceInterface $hashService,
        FilesystemInterface $multimediaStorage,
        MultimediaQueryInterface $multimediaQuery
    ) {
        $this->repository = $repository;
        $this->downloader = $downloader;
        $this->hashService = $hashService;
        $this->multimediaStorage = $multimediaStorage;
        $this->multimediaQuery = $multimediaQuery;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $filename = $record->has(self::NAME_FIELD) ? $record->get(self::NAME_FIELD) : null;
        $url = $record->has(self::URL_FIELD) ? $record->get(self::URL_FIELD) : null;

        Assert::notNull($filename, 'Multimedia import required "name" field not exists');
        Assert::notNull($url, 'Multimedia import required "url" field not exists');

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        $id = $this->multimediaQuery->findIdByFilename($name);

        if (!$id) {
            try {
                $tmpFile = tempnam(sys_get_temp_dir(), $importId->getValue());

                $content = $this->downloader->download($url);
                file_put_contents($tmpFile, $content);
                $file = new File($tmpFile);

                $hash = $this->hashService->calculateHash($file);
                $filename = sprintf('%s.%s', $hash->getValue(), $extension);
                if (!$this->multimediaStorage->has($filename)) {
                    $this->multimediaStorage->write($filename, $content);
                }

                $size = $this->multimediaStorage->getSize($filename);
                $mime = $this->multimediaStorage->getMimetype($filename);

                $multimedia = new Multimedia(
                    MultimediaId::generate(),
                    $name,
                    $extension,
                    $size,
                    $hash,
                    $mime,
                );

                $this->repository->save($multimedia);
                unlink($tmpFile);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }
}
