<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Migration;

use Ergonode\Core\Application\Messenger\CommandBus;
use Ergonode\Multimedia\Application\Service\SuffixGeneratingServiceInterface;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class NameMigrationService
{
    private MultimediaRepositoryInterface $multimediaRepository;

    private MultimediaQueryInterface $multimediaQuery;

    private SuffixGeneratingServiceInterface $generatingService;

    private CommandBus $commandBus;

    public function __construct(
        MultimediaRepositoryInterface $multimediaRepository,
        MultimediaQueryInterface $multimediaQuery,
        SuffixGeneratingServiceInterface $generatingService,
        CommandBus $commandBus
    ) {
        $this->multimediaRepository = $multimediaRepository;
        $this->multimediaQuery = $multimediaQuery;
        $this->generatingService = $generatingService;
        $this->commandBus = $commandBus;
    }

    public function migrateName(MultimediaId $multimediaId): void
    {
        $multimedia = $this->multimediaRepository->load($multimediaId);
        if (null === $multimedia) {
            return;
        }
        $name = $newName = $multimedia->getName();
        $i = 0;
        while ($this->multimediaQuery->findIdByFilename($newName)) {
            $newName = $this->generatingService->generateSuffix($name, $i++);
        }

        $command = new UpdateMultimediaCommand($multimediaId, $newName, $multimedia->getAlt());
        $this->commandBus->dispatch($command);
    }
}
