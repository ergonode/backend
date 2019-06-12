<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface TemplateQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param TemplateId $id
     * @param Language   $language
     *
     * @return array
     */
    public function getTemplate(TemplateId $id, Language $language): array;

    /**
     * @param string $name
     *
     * @return TemplateId|null
     */
    public function findIdByName(string $name): ?TemplateId;
}
