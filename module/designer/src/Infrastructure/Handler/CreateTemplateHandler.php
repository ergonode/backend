<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Handler;

use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Factory\TemplateFactory;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;

/**
 */
class CreateTemplateHandler
{
    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var TemplateGroupQueryInterface
     */
    private $templateGroupQuery;

    /**
     * @param TemplateRepositoryInterface $templateRepository
     * @param TemplateFactory             $templateFactory
     * @param TemplateGroupQueryInterface $templateGroupQuery
     */
    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateFactory $templateFactory,
        TemplateGroupQueryInterface $templateGroupQuery
    ) {
        $this->templateRepository = $templateRepository;
        $this->templateFactory = $templateFactory;
        $this->templateGroupQuery = $templateGroupQuery;
    }

    /**
     * @param CreateTemplateCommand $command
     */
    public function __invoke(CreateTemplateCommand $command)
    {
        $groupId = $this->templateGroupQuery->getDefaultId();

        $template = $this->templateFactory->create(
            $command->getId(),
            $groupId,
            $command->getName(),
            $command->getElements()->toArray(),
            $command->getSections()->toArray(),
            $command->getImageId()
        );

        $this->templateRepository->save($template);
    }
}
