<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\DeleteSourceCommand;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteSourceCommandHandler
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @param SourceRepositoryInterface $sourceRepository
     */
    public function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    /**
     * @param DeleteSourceCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteSourceCommand $command)
    {
        $source = $this->sourceRepository->load($command->getId());

        Assert::notNull($source, sprintf('Can\'t fid source "%s"', $command->getId()->getValue()));

        $this->sourceRepository->delete($source);
    }
}
