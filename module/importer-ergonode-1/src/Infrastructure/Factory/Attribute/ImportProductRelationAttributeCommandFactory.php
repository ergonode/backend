<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportProductRelationAttributeCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeModel;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class ImportProductRelationAttributeCommandFactory implements ImportAttributeCommandFactoryInterface
{

    public function supports(string $type): bool
    {
        return ProductRelationAttribute::TYPE === $type;
    }

    public function create(ImportLineId $id, Import $import, AttributeModel $model): DomainCommandInterface
    {
        return new ImportProductRelationAttributeCommand(
            $id,
            $import->getId(),
            $model->getCode(),
            $model->getType(),
            $model->getName(),
            $model->getHint(),
            $model->getPlaceholder(),
            $model->getScope(),
            $model->getParameters()
        );
    }
}
