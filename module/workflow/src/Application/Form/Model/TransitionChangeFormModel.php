<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Account\Infrastructure\Validator\RoleExists;
use Symfony\Component\Validator\Constraints as Assert;

class TransitionChangeFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=100,
     *       maxMessage="Status name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *     max=500,
     *     maxMessage="Status description is too long. It should contain {{ limit }} characters or less."
     *  )
     * })
     */
    public array $description;

    public ?string $conditionSet;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true),
     *
     *     @RoleExists()
     *
     * })
     */
    public array $roles;

    public function __construct()
    {
        $this->name = [];
        $this->description = [];
        $this->conditionSet = null;
        $this->roles = [];
    }
}
