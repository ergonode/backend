<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Core\Application\Serializer\SerializerInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Psr\Log\LoggerInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class ImportTemplateCommandHandler
{
    private TemplateQueryInterface $templateQuery;
    private TemplateRepositoryInterface $templateRepository;
    private TemplateGroupQueryInterface $templateGroupQuery;
    private ImportRepositoryInterface $importerRepository;
    private LoggerInterface $logger;
    private SerializerInterface $serializer;

    public function __construct(
        TemplateQueryInterface $templateQuery,
        TemplateRepositoryInterface $templateRepository,
        TemplateGroupQueryInterface $templateGroupQuery,
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->templateQuery = $templateQuery;
        $this->templateRepository = $templateRepository;
        $this->templateGroupQuery = $templateGroupQuery;
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public function __invoke(ImportTemplateCommand $command): void
    {
        try {
            $element = $this->serializer->deserialize(
                $command->getProperty(),
                TemplateElementInterface::class,
            );

            if (!$element instanceof TemplateElementInterface) {
                throw new ImportException(
                    sprintf(
                        'Can\'t import template "%s", invalid template element type "%s"',
                        $command->getName(),
                        $command->getType(),
                    )
                );
            }

            $template = $this->action(
                $command->getName(),
                $element
            );

            $this->templateRepository->save($template);
            $this->importerRepository->markLineAsSuccess($command->getId(), $template->getId());
        } catch (ImportException $exception) {
            $this->importerRepository->markLineAsFailure($command->getId());
            $this->importerRepository->addError(
                $command->getImportId(),
                $exception->getMessage(),
                $exception->getParameters()
            );
        } catch (\Exception $exception) {
            $message = 'Can\'t import template {name}';
            $this->importerRepository->markLineAsFailure($command->getId());
            $this->importerRepository->addError($command->getImportId(), $message, ['{name}' => $command->getName()]);
            $this->logger->error($exception);
        }
    }

    /**
     * @throws \Exception
     */
    private function action(string $name, TemplateElementInterface $element): Template
    {
        $templateId = $this->templateQuery->findTemplateIdByCode($name);
        if ($templateId) {
            $template = $this->templateRepository->load($templateId);
        } else {
            $groupId = $this->templateGroupQuery->getDefaultId();
            $template = new Template(
                TemplateId::generate(),
                $groupId,
                $name
            );
        }

        $template->changeName($name);

        $oldElement = $template->getElement($element->getPosition());
        if (!$oldElement instanceof TemplateElementInterface) {
            $template->addElement($element);
        } elseif (!$oldElement->isEqual($element)) {
            $template->changeElement($element);
        }

        return $template;
    }
}
