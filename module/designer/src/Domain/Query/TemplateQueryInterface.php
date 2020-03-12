<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

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

    /**
     * @param string $code
     *
     * @return TemplateId|null
     */
    public function findTemplateIdByCode(string $code): ?TemplateId;
}
