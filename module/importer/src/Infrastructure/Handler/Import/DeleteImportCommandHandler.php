<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\DeleteImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface  $importRepository;

    /**
     * @param ImportRepositoryInterface $importRepository
     */
    public function __construct(ImportRepositoryInterface $importRepository)
    {
        $this->importRepository = $importRepository;
    }

    /**
     * @param DeleteImportCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteImportCommand $command)
    {
        $import = $this->importRepository->load($command->getId());

        Assert::notNull($import, sprintf('Can\'t fid import "%s"', $command->getId()->getValue()));

        $this->importRepository->delete($import);
    }
}
