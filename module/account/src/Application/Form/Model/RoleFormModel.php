<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AccountAssert\RoleNameUnique()
 */
class RoleFormModel
{
    private ?RoleId $roleId;

    /**
     * @Assert\NotBlank(message="Role name is required")
     * @Assert\Length(
     *     max="100",
     *     maxMessage="Role name is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $name;

    /**
     * @Assert\Length(
     *     min="3",
     *     max="500",
     *     minMessage="Role description is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="Role description is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $description;

    /**
     * @var Privilege[]
     *
     * @AccountAssert\PrivilegeRelations()
     */
    public array $privileges;

    public function __construct(RoleId $roleId = null)
    {
        $this->roleId = $roleId;
        $this->name = null;
        $this->description = null;
        $this->privileges = [];
    }

    public function getRoleId(): ?RoleId
    {
        return $this->roleId;
    }
}
