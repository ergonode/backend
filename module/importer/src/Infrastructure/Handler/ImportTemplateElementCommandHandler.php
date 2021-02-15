<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Core\Application\Serializer\SerializerInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateElementCommand;
use Ergonode\Importer\Infrastructure\Action\TemplateElementImportAction;

class ImportTemplateElementCommandHandler
{
    private TemplateElementImportAction $action;
    private TemplateQueryInterface $templateQuery;
    private TemplateRepositoryInterface $templateRepository;
    private ImportRepositoryInterface $importerRepository;
    private LoggerInterface $logger;
    private SerializerInterface $serializer;

    /**
     * @param TemplateElementImportAction $action
     * @param TemplateQueryInterface      $templateQuery
     * @param TemplateRepositoryInterface $templateRepository
     * @param ImportRepositoryInterface   $importerRepository
     * @param LoggerInterface             $logger
     * @param SerializerInterface         $serializer
     */
    public function __construct(
        TemplateElementImportAction $action,
        TemplateQueryInterface $templateQuery,
        TemplateRepositoryInterface $templateRepository,
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->action = $action;
        $this->templateQuery = $templateQuery;
        $this->templateRepository = $templateRepository;
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public function __invoke(ImportTemplateElementCommand $command): void
    {
        $name = $command->getName();

        try {
            $element = $this->serializer->deserialize($command->getProperty(), TemplateElementInterface::class,);

            if (!$element instanceof TemplateElementInterface) {
                throw new ImportException(
                    sprintf(
                        'Can\'t import template "%s", invalid template element type "%s"',
                        $command->getName(),
                        $command->getType(),
                    )
                );
            }
            $template = $this->action->action($name, $element);

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
