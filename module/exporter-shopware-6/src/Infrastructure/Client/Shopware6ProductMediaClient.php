<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6MultimediaRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media\GetMediaDefaultFolderList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media\PostCreateMediaAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media\PostUploadFile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6DefaultFolderException;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Media;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6MediaDefaultFolder;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class Shopware6ProductMediaClient
{
    private Shopware6Connector $connector;

    private FilesystemInterface $multimediaStorage;

    private Shopware6MultimediaRepositoryInterface $multimediaRepository;

    public function __construct(
        Shopware6Connector $connector,
        FilesystemInterface $multimediaStorage,
        Shopware6MultimediaRepositoryInterface $multimediaRepository
    ) {
        $this->connector = $connector;
        $this->multimediaStorage = $multimediaStorage;
        $this->multimediaRepository = $multimediaRepository;
    }

    /**
     * @throws Shopware6DefaultFolderException
     */
    public function findOrCreateMedia(Shopware6Channel $channel, Multimedia $multimedia): string
    {
        if ($this->multimediaRepository->exists($channel->getId(), $multimedia->getId())) {
            return $this->multimediaRepository->load($channel->getId(), $multimedia->getId());
        }
        $folder = $this->getProductFolderId($channel);
        if (null === $folder) {
            throw new Shopware6DefaultFolderException();
        }
        $media = $this->createMediaResource($channel, $folder);
        Assert::notNull($media);
        $this->upload($channel, $media, $multimedia);
        $this->multimediaRepository->save($channel->getId(), $multimedia->getId(), $media->getId());

        return $media->getId();
    }

    private function upload(Shopware6Channel $channel, Shopware6Media $media, Multimedia $multimedia): void
    {
        $content = $this->multimediaStorage->read($multimedia->getFileName());

        $action = new PostUploadFile($media->getId(), $content, $multimedia);
        $this->connector->execute($channel, $action);
    }

    private function createMediaResource(
        Shopware6Channel $channel,
        Shopware6MediaDefaultFolder $folder
    ): ?Shopware6Media {
        $action = new PostCreateMediaAction($folder->getMediaFolderId(), true);

        return $this->connector->execute($channel, $action);
    }

    private function getProductFolderId(Shopware6Channel $channel): ?Shopware6MediaDefaultFolder
    {
        $query = new Shopware6QueryBuilder();
        $query->equals('entity', 'product');

        $action = new GetMediaDefaultFolderList($query);

        $folderList = $this->connector->execute($channel, $action);
        if (is_array($folderList) && count($folderList) > 0) {
            return reset($folderList);
        }

        return null;
    }
}
