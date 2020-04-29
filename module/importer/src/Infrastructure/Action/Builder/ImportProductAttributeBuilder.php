<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Builder;

use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;

/**
 */
class ImportProductAttributeBuilder implements ProductImportBuilderInterface
{
    /**
     * @param ImportedProduct $product
     * @param Record          $record
     *
     * @return ImportedProduct
     *
     * @throws \Exception
     */
    public function build(ImportedProduct $product, Record $record): ImportedProduct
    {
        foreach ($record->getValues() as $key => $value) {
            if ('' !== $value && null !== $value) {
                $product->attributes[$key] = $value;
            }
        }

        return $product;
    }
}
