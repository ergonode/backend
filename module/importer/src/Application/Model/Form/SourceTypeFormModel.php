<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Model\Form;

use Symfony\Component\Validator\Constraints as Assert;

class SourceTypeFormModel
{
    /**
     * @Assert\NotBlank(
     *     message="Profile type is required",
     *     )
     */
    public ?string $type = null;
}
