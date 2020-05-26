<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Model;

use Ergonode\Exporter\Infrastructure\Validator\ExportProfileNotExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ChannelCreateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(),
     * @Assert\Length(max=255, maxMessage="Channel name is to long, It should have {{ limit }} character or less.")
     */
    public ?string $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Export profile is required")
     *
     * @ExportProfileNotExists()
     */
    public ?string $exportProfileId;

    /**
     */
    public function __construct()
    {
        $this->name = null;
        $this->exportProfileId = null;
    }
}
