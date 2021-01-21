<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeParametersModel;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\AttributeFactoryResolver;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Psr\Log\LoggerInterface;

class ImportAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    private AttributeFactoryResolver $attributeFactoryResolver;

    private ImportRepositoryInterface $importerRepository;

    private LoggerInterface $logger;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeFactoryResolver $attributeFactoryResolver,
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeFactoryResolver = $attributeFactoryResolver;
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
    }

    public function __invoke(ImportAttributeCommand $command): void
    {
        try {
            $attribute = $this->attributeRepository->load($command->getId());

            if (null === $attribute) {
                $factory = $this->attributeFactoryResolver->resolve($command->getType());
                $attribute = $factory->create(
                    $command->getId(),
                    $command->getCode(),
                    $command->getScope(),
                    new TranslatableString($command->getLabel()),
                    new TranslatableString($command->getHint()),
                    new TranslatableString($command->getPlaceholder()),
                    new AttributeParametersModel($command->getParameters())
                );
            } else {
                $attribute->changeLabel(new TranslatableString($command->getLabel()));
                $attribute->changeHint(new TranslatableString($command->getHint()));
                $attribute->changePlaceholder(new TranslatableString($command->getPlaceholder()));
                $attribute->changeScope($command->getScope());
            }

            $this->attributeRepository->save($attribute);
            $this->importerRepository->addLine($command->getImportId(), $attribute->getId(), $attribute->getType());
        } catch (ImportException $exception) {
            $this->importerRepository->addError(
                $command->getImportId(),
                $exception->getMessage(),
                $exception->getParameters()
            );
        } catch (\Exception $exception) {
            $message = 'Can\'t import attribute product {code}';
            $this->importerRepository->addError($command->getImportId(), $message, ['{code}' => $command->getCode()]);
            $this->logger->error($exception);
        }
    }
}
