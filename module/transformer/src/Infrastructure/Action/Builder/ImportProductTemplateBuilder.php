<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action\Builder;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class ImportProductTemplateBuilder implements ProductImportBuilderInterface
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
        $templateCode = $record->get('esa_template')->getValue();
        $templateId = TemplateId::fromKey($templateCode);

        $product->attributes['esa_template'] = new StringValue($templateId->getValue());

        return $product;
    }
}
