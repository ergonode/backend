<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints as AccountAssert;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Infrastructure\Validator\RoleNameUnique;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RoleNameUnique()
 */
class RoleFormModel
{
    /**
     * @var RoleId|null
     */
    private ?RoleId $roleId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Role name is required")
     * @Assert\Length(
     *     max="100",
     *     maxMessage="Role name is too long, should have at least {{ limit }} characters"
     * )
     *
     */
    public ?string $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Role description is required")
     * @Assert\Length(
     *     min="3",
     *     max="500",
     *     minMessage="Role description is too short, should have at least {{ limit }} characters",
     *     maxMessage="Role description is too long, should have at most {{ limit }} characters"
     * )
     */
    public ?string $description;

    /**
     * @var Privilege[]
     *
     * @AccountAssert\ConstraintPrivilegeRelations()
     */
    public array $privileges;

    /**
     * @param RoleId|null $roleId
     */
    public function __construct(RoleId $roleId = null)
    {
        $this->roleId = $roleId;
        $this->name = null;
        $this->description = null;
        $this->privileges = [];
    }

    /**
     * @return RoleId|null
     */
    public function getRoleId(): ?RoleId
    {
        return $this->roleId;
    }
}
