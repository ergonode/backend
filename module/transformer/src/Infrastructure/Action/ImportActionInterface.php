<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;

/**
 */
interface ImportActionInterface
{
    /**
     * @param Record        $record
     * @param ImportedProduct $product
     */
    public function action(Record $record, ImportedProduct $product): void;

    /**
     * @return string
     */
    public function getType(): string;
}
