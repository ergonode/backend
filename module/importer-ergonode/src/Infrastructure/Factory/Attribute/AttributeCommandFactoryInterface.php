<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Attribute;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\AttributeModel;

/**
 */
interface AttributeCommandFactoryInterface
{
    /**
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * @param Import         $import
     * @param AttributeModel $model
     * @return DomainCommandInterface
     */
    public function create(Import $import, AttributeModel $model): DomainCommandInterface;
}
