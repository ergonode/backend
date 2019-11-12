<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain\Query;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface NotificationQueryInterface
{
    /**
     * @param UserId   $id
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(UserId $id, Language $language): DataSetInterface;
}
