<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\DeleteMultimediaCommand;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class DeleteMultimediaCommandHandler
{
    private MultimediaRepositoryInterface $multimediaRepository;

    private FilesystemInterface $multimediaStorage;

    public function __construct(
        MultimediaRepositoryInterface $multimediaRepository,
        FilesystemInterface $multimediaStorage
    ) {
        $this->multimediaRepository = $multimediaRepository;
        $this->multimediaStorage = $multimediaStorage;
    }

    public function __invoke(DeleteMultimediaCommand $command): void
    {
        $multimedia = $this->multimediaRepository->load($command->getId());

        Assert::notNull($multimedia, sprintf('Can\'t fid multimedia "%s"', $command->getId()->getValue()));

        $this->multimediaRepository->delete($multimedia);

        $filename = sprintf('%s.%s', $multimedia->getId(), $multimedia->getExtension());
        if ($this->multimediaStorage->has($filename)) {
            $this->multimediaStorage->delete($filename);
        }
    }
}
