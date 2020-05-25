<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Transformer\Domain\Model\Record;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;

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
     * @var KernelInterface
     */
    private KernelInterface $kernel;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param MultimediaRepositoryInterface $repository
     * @param DownloaderInterface           $downloader
     * @param KernelInterface               $kernel
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        MultimediaRepositoryInterface $repository,
        DownloaderInterface $downloader,
        KernelInterface $kernel,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->downloader = $downloader;
        $this->kernel = $kernel;
        $this->commandBus = $commandBus;
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
        $cacheDir = sprintf('%s/import-%s', $this->kernel->getCacheDir(), $importId->getValue());

        $multimedia = $this->repository->load($id);

        if (!$multimedia) {
            try {
                $content = $this->downloader->download($url);
                $filePath = sprintf('%s/%s', $cacheDir, $name);
                $this->saveFile($filePath, $content);
                $file = new File($filePath);
                $multimediaId = new MultimediaId($id->getValue());
                $command = new AddMultimediaCommand($multimediaId, $file);
                $this->commandBus->dispatch($command, true);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }

    /**
     * @param $dir
     * @param $contents
     */
    public function saveFile($dir, $contents): void
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }

        file_put_contents("$dir/$file", $contents);
    }
}
