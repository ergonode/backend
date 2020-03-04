<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Provider;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UserIdsProvider
{
    /**
     * @var RoleQueryInterface
     */
    private RoleQueryInterface $query;

    /**
     * @param RoleQueryInterface $query
     */
    public function __construct(RoleQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $roleIds
     *
     * @return array
     */
    public function getUserIds(array $roleIds = []): array
    {
        $result = [];
        Assert::allIsInstanceOf($roleIds, RoleId::class);
        foreach ($roleIds as $roleId) {
            $userIds = $this->query->getAllRoleUsers($roleId);
            foreach ($userIds as $userId) {
                $result[$userId->getValue()] = $userId;
            }
        }

        return array_values($result);
    }
}
