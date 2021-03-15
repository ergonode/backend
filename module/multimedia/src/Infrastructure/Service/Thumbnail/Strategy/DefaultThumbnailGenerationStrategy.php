<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail\Strategy;

use Ergonode\Multimedia\Infrastructure\Service\Thumbnail\ThumbnailGenerationStrategyInterface;

class DefaultThumbnailGenerationStrategy implements ThumbnailGenerationStrategyInterface
{
    public const MAX_WIDTH = 800;

    public function supported(string $name): bool
    {
        return 'default' === $name;
    }

    /**
     * @throws \ImagickException
     */
    public function generate(\Imagick $imagick): \Imagick
    {
        if ($imagick->getImageWidth() > self::MAX_WIDTH) {
            $imagick->scaleImage(self::MAX_WIDTH, 0);
        }

        return $imagick;
    }
}
