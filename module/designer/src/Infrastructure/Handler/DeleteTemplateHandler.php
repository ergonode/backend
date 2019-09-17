<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Designer\Domain\Command\DeleteTemplateCommand;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTemplateHandler
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $repository;

    /**
     * @param TemplateRepositoryInterface $repository
     */
    public function __construct(TemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteTemplateCommand $command
     */
    public function __invoke(DeleteTemplateCommand $command)
    {
        $template = $this->repository->load($command->getId());
        Assert::notNull($template);

        $template->remove();

        $this->repository->save($template);
    }
}
