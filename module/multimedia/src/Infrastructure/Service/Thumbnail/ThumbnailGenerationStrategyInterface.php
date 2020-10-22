<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail;

interface ThumbnailGenerationStrategyInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function supported(string $name): bool;

    /**
     * @param \Imagick $imagick
     *
     * @return \Imagick
     *
     * @throws \ImagickException
     */
    public function generate(\Imagick $imagick): \Imagick;
}
