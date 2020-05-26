<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Command\CreateTemplateCommand;
use Ergonode\Designer\Domain\Command\UpdateTemplateCommand;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;

/**
 */
class TemplateImportAction implements ImportActionInterface
{
    public const TYPE = 'TEMPLATE';

    public const CODE_FIELD = 'code';

    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     * @var TemplateRepositoryInterface
     */
    private TemplateRepositoryInterface $templateRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TemplateQueryInterface      $query
     * @param TemplateRepositoryInterface $templateRepository
     * @param CommandBusInterface         $commandBus
     */
    public function __construct(
        TemplateQueryInterface $query,
        TemplateRepositoryInterface $templateRepository,
        CommandBusInterface $commandBus
    ) {
        $this->query = $query;
        $this->templateRepository = $templateRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $code = $record->has(self::CODE_FIELD) ? $record->get(self::CODE_FIELD) : null;

        Assert::notNull($code, 'Template import required "code" field not exists');

        $template = null;
        $templateId = $this->query->findTemplateIdByCode($code);

        if ($templateId) {
            $template = $this->templateRepository->load($templateId);
        }

        if (!$template) {
            $command = new CreateTemplateCommand($code, new ArrayCollection());
        } else {
            $command = new UpdateTemplateCommand($templateId, $code, new ArrayCollection());
        }

        $this->commandBus->dispatch($command, true);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
