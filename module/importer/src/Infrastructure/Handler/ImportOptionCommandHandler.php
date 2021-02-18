<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\OptionImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Psr\Log\LoggerInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;

class ImportOptionCommandHandler
{
    private OptionImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        OptionImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportOptionCommand $command): void
    {
        try {
            if (!AttributeCode::isValid($command->getCode())) {
                throw new ImportException('Attribute code {code} is not valid', ['{code}' => $command->getCode()]);
            }

            if (!OptionKey::isValid($command->getOptionKey())) {
                throw new ImportException('Option key {code} is not valid', ['{code}' => $command->getOptionKey()]);
            }

            $optionId = $this->action->action(
                new AttributeCode($command->getCode()),
                new OptionKey($command->getOptionKey()),
                $command->getTranslation()
            );
            $this->repository->markLineAsSuccess($command->getId(), $optionId);
        } catch (ImportException $exception) {
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import options {option} for attribute {attribute}';

            $parameters = [
                '{option}' => $command->getOptionKey(),
                '{attribute}' => $command->getCode(),
            ];

            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $message, $parameters);
            $this->logger->error($exception);
        }
    }
}
