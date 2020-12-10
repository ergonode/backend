<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;

class ImportTemplateCommandHandler
{
    private TemplateRepositoryInterface $templateRepository;
    private TemplateGroupQueryInterface $templateGroupQuery;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateGroupQueryInterface $templateGroupQuery
    ) {
        $this->templateRepository = $templateRepository;
        $this->templateGroupQuery = $templateGroupQuery;
    }

    /**
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
