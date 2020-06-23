<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

/**
 */
class AvatarExtensionProvider
{
    /**
     * @return array
     */
    public function dictionary(): array
    {
        return [
            'gif',
            'jpeg',
            'jpg',
            'bmp',
            'tif',
            'tiff',
            'png',
        ];
    }
}
