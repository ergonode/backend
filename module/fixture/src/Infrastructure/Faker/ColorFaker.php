<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Color;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ColorFaker extends BaseProvider
{
    /**
     * @param string $color
     *
     * @return Color
     */
    public function color(string $color): Color
    {
        return new Color($color);
    }
}
