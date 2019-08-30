<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Designer\Domain\Checker\TemplateRelationChecker;
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
     * @var TemplateRelationChecker
     */
    private $checker;

    /**
     * @param TemplateRepositoryInterface $repository
     * @param TemplateRelationChecker     $checker
     */
    public function __construct(TemplateRepositoryInterface $repository, TemplateRelationChecker $checker)
    {
        $this->repository = $repository;
        $this->checker = $checker;
    }

    /**
     * @param DeleteTemplateCommand $command
     */
    public function __invoke(DeleteTemplateCommand $command)
    {
        $template = $this->repository->load($command->getId());

        Assert::notNull($template);

        if ($this->checker->hasRelations($template)) {
            throw new \RuntimeException('Can\'t delete template witch relations');
        }

        $template->remove();

        $this->repository->save($template);
    }
}
