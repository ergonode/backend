<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api\Factory;

use Ergonode\BatchAction\Application\Form\Model\BatchActionFilterFormModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionIds;
use Ergonode\SharedKernel\Domain\AggregateId;

class BatchActionFilterFactory
{
    public function create(BatchActionFilterFormModel $model): BatchActionFilter
    {
        $ids = null;
        if ($model->ids ?? null) {
            $list = [];
            foreach ($model->ids->list as $id) {
                $list[] = new AggregateId($id);
            }
            $ids = new BatchActionIds($list, $model->ids->included);
        }

        return new BatchActionFilter($ids, $model->query ?? null);
    }
}
