<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Core\Application\Serializer\SerializerInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Psr\Log\LoggerInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

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
            $position = new Position($command->getX(), $command->getY());
            $size = new Size($command->getWidth(), $command->getHeight());

            $property = $this->serializer->deserialize(
                $command->getProperty(),
                TemplateElementPropertyInterface::class,
            );

            $template = $this->action(
                $command->getName(),
                $command->getType(),
                $position,
                $size,
                $property
            );

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

    /**
     * @throws \Exception
     */
    private function action(
        string $name,
        string $type,
        Position $position,
        Size $size,
        TemplateElementPropertyInterface $properties
    ): Template {
        $importedElement = new TemplateElement(
            $position,
            $size,
            $type,
            $properties
        );

        $templateId = $this->templateQuery->findTemplateIdByCode($name);
        if ($templateId) {
            $template = $this->templateRepository->load($templateId);
            if ($template) {
                $template->changeName($name);

                $element = $template->getElement($position);
                if (!$element instanceof TemplateElement) {
                    $template->addElement($importedElement);
                } elseif (!$element->isEqual($importedElement)) {
                    $template->changeElement($importedElement);
                }

                return $template;
            }
        }

        $groupId = $this->templateGroupQuery->getDefaultId();
        $template = new Template(
            TemplateId::generate(),
            $groupId,
            $name
        );
        $template->addElement($importedElement);

        return $template;
    }
}
