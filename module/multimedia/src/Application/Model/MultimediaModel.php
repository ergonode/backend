<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

class MultimediaModel
{
    /**
     * @Assert\NotBlank(),
     * @Assert\Length(
     *   max=128,
     *   maxMessage="Multimedia name is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $name = null;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=128,
     *       maxMessage="Multimedia alt is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $alt = [];
}
