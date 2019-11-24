<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Builder\Notification;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Product\Domain\Entity\ProductId;

/**
 */
class StatusChangedNotificationBuilder
{
    private const MESSAGE = 'Product {{ sku }} change status from {{ from }} to {{ to }}';

    /**
     * @param RoleId    $roleId
     * @param UserId    $authorId
     * @param ProductId $productId
     *
     * @return SendNotificationCommand
     */
    public function build(RoleId $roleId, UserId $authorId, ProductId $productId): SendNotificationCommand
    {
        return new SendNotificationCommand(
            'Product ',
            $roleId,
            $authorId
        );
    }
}
