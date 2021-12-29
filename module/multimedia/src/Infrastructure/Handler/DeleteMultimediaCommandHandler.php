<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\DeleteMultimediaCommand;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteMultimediaCommandHandler
{
    private MultimediaRepositoryInterface $multimediaRepository;

    public function __construct(MultimediaRepositoryInterface $multimediaRepository)
    {
        $this->multimediaRepository = $multimediaRepository;
    }

    public function __invoke(DeleteMultimediaCommand $command): void
    {
        $multimedia = $this->multimediaRepository->load($command->getId());

        Assert::notNull($multimedia, sprintf('Can\'t fid multimedia "%s"', $command->getId()->getValue()));

        $this->multimediaRepository->delete($multimedia);
    }
}
