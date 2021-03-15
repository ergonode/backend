<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateSegmentFormModel
{
    /**
     * @Assert\Uuid()
     */
    public ?string $conditionSetId;

    /**
     * @var array
     */
    public array $name;

    /**
     * @var array
     */
    public array $description;

    public function __construct()
    {
        $this->conditionSetId = null;
        $this->name = [];
        $this->description = [];
    }
}
