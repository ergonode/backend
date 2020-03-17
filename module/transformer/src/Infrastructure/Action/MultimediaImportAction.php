<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ramsey\Uuid\Uuid;

/**
 */
class MultimediaImportAction implements ImportActionInterface
{
    private const NAMESPACE = 'e1f84ee9-14f2-4e52-981a-b6b82006ada8';

    public const TYPE = 'MULTIMEDIA';

    public const ID_FIELD = 'id';
    public const URL_FIELD = 'url';
    public const NAME_FIELD = 'name';


    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

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
     * @param KernelInterface               $kernel
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        MultimediaRepositoryInterface $repository,
        KernelInterface $kernel,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
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
        $id = $record->has(self::ID_FIELD) ? new MultimediaId($record->get(self::ID_FIELD)->getValue()) : null;
        $name = $record->has(self::NAME_FIELD) ? $record->get(self::NAME_FIELD)->getValue() : null;
        $url = $record->has(self::URL_FIELD) ? $record->get(self::URL_FIELD)->getValue() : null;
        Assert::notNull($id, 'Multimedia import required "id" field not exists');
        Assert::notNull($name, 'Multimedia import required "name" field not exists');
        Assert::notNull($url, 'Multimedia import required "url" field not exists');
        $cacheDir = sprintf('%s/import-%s', $this->kernel->getCacheDir(), $importId->getValue());

        $multimedia = $this->repository->load($id);

        if (!$multimedia) {
            try {
                $content = file_get_contents($url);
                $filePath = sprintf('%s/%s', $cacheDir, $name);
                $this->saveFile($filePath, $content);
                $file = new File($filePath);
                $uuid  = Uuid::uuid5(self::NAMESPACE, $url);
                $multimediaId = new MultimediaId($uuid);
                $command = new AddMultimediaCommand($multimediaId, $file);
                $this->commandBus->dispatch($command);
            } catch (\Throwable $exception) {
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
