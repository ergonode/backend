<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class MultimediaModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(),
     * @Assert\Length(
     *   max=64,
     *   maxMessage="Multimedia alt is to long, It should have {{ limit }} character or less."
     * )
     */
    public ?string $name = null;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=64,
     *       maxMessage="Multimedia alt is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $alt = [];
}
