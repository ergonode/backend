<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Transformer\Domain\Model\Record;
use Symfony\Component\HttpFoundation\File\File;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use League\Flysystem\FilesystemInterface;

/**
 */
class MultimediaImportAction implements ImportActionInterface
{
    public const TYPE = 'MULTIMEDIA';

    public const ID_FIELD = 'id';
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
     * @param MultimediaRepositoryInterface   $repository
     * @param DownloaderInterface             $downloader
     * @param HashCalculationServiceInterface $hashService
     * @param FilesystemInterface             $multimediaStorage
     */
    public function __construct(
        MultimediaRepositoryInterface $repository,
        DownloaderInterface $downloader,
        HashCalculationServiceInterface $hashService,
        FilesystemInterface $multimediaStorage
    ) {
        $this->repository = $repository;
        $this->downloader = $downloader;
        $this->hashService = $hashService;
        $this->multimediaStorage = $multimediaStorage;
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
        $id = $record->has(self::ID_FIELD) ? new MultimediaId($record->get(self::ID_FIELD)) : null;
        $name = $record->has(self::NAME_FIELD) ? $record->get(self::NAME_FIELD) : null;
        $url = $record->has(self::URL_FIELD) ? $record->get(self::URL_FIELD) : null;
        Assert::notNull($id, 'Multimedia import required "id" field not exists');
        Assert::notNull($name, 'Multimedia import required "name" field not exists');
        Assert::notNull($url, 'Multimedia import required "url" field not exists');

        $multimedia = $this->repository->load($id);

        if (!$multimedia) {
            try {
                $tmpFile = tempnam(sys_get_temp_dir(), $id->getValue());
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $originalFilename = pathinfo($name, PATHINFO_FILENAME);

                $content = $this->downloader->download($url);
                file_put_contents($tmpFile, $content);

                $file = new File($tmpFile);

                $hash = $this->hashService->calculateHash($file);
                $filename = sprintf('%s.%s', $hash->getValue(), $extension);
                if (!$this->multimediaStorage->has($hash)) {
                    $this->multimediaStorage->write($hash, $content);
                }

                $multimedia = new Multimedia(
                    $id,
                    $originalFilename,
                    $extension,
                    $this->multimediaStorage->getSize($filename),
                    $hash,
                    $this->multimediaStorage->getMimetype($filename)
                );

                $this->repository->save($multimedia);
                unlink($tmpFile);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }
}
