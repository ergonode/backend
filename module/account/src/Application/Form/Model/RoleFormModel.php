<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints as AccountAssert;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class RoleFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Role name is required")
     * @Assert\Length(max="100")
     */
    public ?string $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Role description is required")
     * @Assert\Length(min="3", max="500")
     */
    public ?string $description;

    /**
     * @var Privilege[]
     *
     * @AccountAssert\ConstraintPrivilegeRelations()
     */
    public array $privileges;

    /**
     */
    public function __construct()
    {
        $this->name = null;
        $this->description = null;
        $this->privileges = [];
    }
}
