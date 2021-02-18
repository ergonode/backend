<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeParametersModel;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\AttributeFactoryResolver;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Psr\Log\LoggerInterface;

class ImportAttributeCommandHandler
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    private AttributeFactoryResolver $attributeFactoryResolver;

    private ImportRepositoryInterface $importerRepository;

    private LoggerInterface $logger;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        AttributeFactoryResolver $attributeFactoryResolver,
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
        $this->attributeFactoryResolver = $attributeFactoryResolver;
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
    }

    public function __invoke(ImportAttributeCommand $command): void
    {
        try {
            if (!AttributeCode::isValid($command->getCode())) {
                throw new ImportException('Attribute code {code} is not valid', ['{code}' => $command->getCode()]);
            }

            if (!AttributeScope::isValid($command->getScope())) {
                throw new ImportException('Attribute Scope {scope} is not valid', ['{scope}' => $command->getScope()]);
            }

            $attribute = $this->action(
                new AttributeCode($command->getCode()),
                $command->getType(),
                new AttributeScope($command->getScope()),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeParametersModel($command->getParameters())
            );

            $this->attributeRepository->save($attribute);
            $this->importerRepository->markLineAsSuccess($command->getId(), $attribute->getId());
        } catch (ImportException $exception) {
            $this->importerRepository->markLineAsFailure($command->getId());
            $this->importerRepository->addError(
                $command->getImportId(),
                $exception->getMessage(),
                $exception->getParameters()
            );
        } catch (\Exception $exception) {
            $message = 'Can\'t import attribute product {code}';
            $this->importerRepository->markLineAsFailure($command->getId());
            $this->importerRepository->addError($command->getImportId(), $message, ['{code}' => $command->getCode()]);
            $this->logger->error($exception);
        }
    }

    private function action(
        AttributeCode $attributeCode,
        string $type,
        AttributeScope $scope,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeParametersModel $parameters
    ): AbstractAttribute {
        $attributeId = $this->attributeQuery->findAttributeIdByCode($attributeCode);
        if ($attributeId) {
            $attribute = $this->attributeRepository->load($attributeId);
            if ($attribute) {
                $attribute->changeLabel($label);
                $attribute->changeHint($hint);
                $attribute->changePlaceholder($placeholder);
                $attribute->changeScope($scope);

                return $attribute;
            }
        }

        $factory = $this->attributeFactoryResolver->resolve($type);

        return $factory->create(
            AttributeId::fromKey($attributeCode->getValue()),
            $attributeCode,
            $scope,
            $label,
            $hint,
            $placeholder,
            $parameters
        );
    }
}
