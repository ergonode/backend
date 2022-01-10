<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Factory;

use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Symfony\Component\HttpFoundation\File\File;
use League\Flysystem\FilesystemInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Core\Infrastructure\Service\Header;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Core\Infrastructure\Exception\DownloaderException;
use Ergonode\Core\Infrastructure\Exception\FileNotFoundDownloaderException;
use Ergonode\Core\Infrastructure\Exception\AccessDeniedDownloaderException;
use Ergonode\Core\Infrastructure\Exception\BadRequestDownloaderException;

class MultimediaFileFactory
{
    private FilesystemInterface $multimediaStorage;

    private HashCalculationServiceInterface $hashService;

    private DownloaderInterface $downloader;

    private MultimediaExtensionProvider $provider;

    public function __construct(
        FilesystemInterface $multimediaStorage,
        HashCalculationServiceInterface $hashService,
        DownloaderInterface $downloader,
        MultimediaExtensionProvider $provider
    ) {
        $this->multimediaStorage = $multimediaStorage;
        $this->hashService = $hashService;
        $this->downloader = $downloader;
        $this->provider = $provider;
    }

    /**
     * @param Header[] $headers
     */
    public function create(string $name, string $url, array $headers = []): AbstractMultimedia
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $tmpFile = tempnam(sys_get_temp_dir(), Uuid::uuid4()->toString());

        try {
            $content = $this->downloader->download($url, $headers, $this->provider->mimeDictionary());
        } catch (FileNotFoundDownloaderException $exception) {
            throw new ImportException('Can\'t download media from url {url}, file not found', ['{url}' => $url]);
        } catch (AccessDeniedDownloaderException $exception) {
            throw new ImportException('Can\'t download media from url {url}, access denied', ['{url}' => $url]);
        } catch (BadRequestDownloaderException $exception) {
            throw new ImportException('Can\'t download media from url {url}, bad request', ['{url}' => $url]);
        } catch (DownloaderException $exception) {
            throw new ImportException('Can\'t download media from url {url}', ['{url}' => $url]);
        }

        file_put_contents($tmpFile, $content);

        $file = new File($tmpFile);

        $id = MultimediaId::generate();
        $hash = $this->hashService->calculateHash($file);

        $filename = sprintf('%s.%s', $id->getValue(), $extension);
        $this->multimediaStorage->write($filename, $content);
        $size = $this->multimediaStorage->getSize($filename);
        $mime = $this->multimediaStorage->getMimetype($filename);

        unlink($tmpFile);

        return new Multimedia(
            $id,
            $name,
            $extension,
            $size,
            $hash,
            $mime,
        );
    }
}
