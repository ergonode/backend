<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Handler\Import;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ImporterErgonode\Domain\Command\Import\ImportTemplateCommand;

/**
 */
final class ImportTemplateCommandHandler
{
    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    /**
     * @var TemplateGroupQueryInterface
     */
    private TemplateGroupQueryInterface $templateGroupQuery;

    /**
     * @param TemplateRepositoryInterface $templateRepository
     * @param TemplateGroupQueryInterface $templateGroupQuery
     */
    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateGroupQueryInterface $templateGroupQuery
    ) {
        $this->templateRepository = $templateRepository;
        $this->templateGroupQuery = $templateGroupQuery;
    }

    /**
     * @param ImportTemplateCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportTemplateCommand $command): void
    {
        $template = $this->templateRepository->load($command->getId());

        $importedElement = new TemplateElement(
            $command->getPosition(),
            $command->getSize(),
            $command->getType(),
            $command->getProperty()
        );

        if (null === $template) {
            $groupId = $this->templateGroupQuery->getDefaultId();
            $template = new Template(
                $command->getId(),
                $groupId,
                $command->getName()
            );
            $template->addElement($importedElement);
        } else {
            $template->changeName($command->getName());

            $element = $template->getElement($command->getPosition());
            if (!$element instanceof TemplateElement) {
                $template->addElement($importedElement);
            } elseif (!$element->isEqual($importedElement)) {
                $template->changeElement($importedElement);
            }
        }

        $this->templateRepository->save($template);
    }
}
