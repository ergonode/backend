<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail;

interface ThumbnailGenerationStrategyInterface
{
    public function supported(string $name): bool;

    /**
     * @throws \ImagickException
     */
    public function generate(\Imagick $imagick): \Imagick;
}
