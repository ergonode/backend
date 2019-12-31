<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;

/**
 */
interface TemplateQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    public function findTemplateIdByAttributeId(AttributeId $attributeId): array;

    /**
     * @param TemplateId $templateId
     *
     * @return ProductId[]
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array;

    /**
     * @param ProductId $productId
     *
     * @return TemplateId
     */
    public function findProductTemplateId(ProductId $productId): TemplateId;
}
