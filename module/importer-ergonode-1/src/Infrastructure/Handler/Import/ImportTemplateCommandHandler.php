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
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

class ImportTemplateCommandHandler
{
    private TemplateRepositoryInterface $templateRepository;
    private TemplateGroupQueryInterface $templateGroupQuery;
    private ImportRepositoryInterface $importerRepository;
    private LoggerInterface $logger;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        TemplateGroupQueryInterface $templateGroupQuery,
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger
    ) {
        $this->templateRepository = $templateRepository;
        $this->templateGroupQuery = $templateGroupQuery;
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
    }

    public function __invoke(ImportTemplateCommand $command): void
    {
        try {
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
            $this->importerRepository->addLine($command->getImportId(), $template->getId(), 'TEMPLATE');
        } catch (ImportException $exception) {
            $this->importerRepository->addError(
                $command->getImportId(),
                $exception->getMessage(),
                $exception->getParameters()
            );
        } catch (\Exception $exception) {
            $message = 'Can\'t import template {name}';
            $this->importerRepository->addError($command->getImportId(), $message, ['{name}' => $command->getName()]);
            $this->logger->error($exception);
        }
    }
}
