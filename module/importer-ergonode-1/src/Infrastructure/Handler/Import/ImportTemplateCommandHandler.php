<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

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
            $newElement = $command->getElement();


            if (null === $template) {
                $groupId = $this->templateGroupQuery->getDefaultId();
                $template = new Template(
                    $command->getId(),
                    $groupId,
                    $command->getName()
                );
                $template->addElement($newElement);
            } else {
                $template->changeName($command->getName());

                $element = $template->getElement($command->getPosition());
                if (!$element instanceof TemplateElementInterface) {
                    $template->addElement($newElement);
                } elseif (!$element->isEqual($newElement)) {
                    $template->changeElement($newElement);
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
