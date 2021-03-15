<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Ergonode\Attribute\Domain\Query\AttributeGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;

class AttributeGridQueryDecorator implements AttributeGridQueryInterface
{
    private AttributeGridQueryInterface $query;

    public function __construct(AttributeGridQueryInterface $query)
    {
        $this->query = $query;
    }

    public function getDataSetQuery(Language $language, bool $system = false): QueryBuilder
    {
        $query = $this->query->getDataSetQuery($language, $system);
        $query->addSelect('
            (
                SELECT COALESCE(jsonb_agg(dta.template_id),\'[]\')  
                FROM designer.template_attribute AS dta
                WHERE dta.attribute_id = a.id
            ) AS templates');

        return $query;
    }
}
