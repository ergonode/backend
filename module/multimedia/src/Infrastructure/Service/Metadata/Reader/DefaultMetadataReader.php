<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata\Reader;

use Ergonode\Multimedia\Infrastructure\Service\Metadata\MetadataReaderInterface;

class DefaultMetadataReader implements MetadataReaderInterface
{
    /**
     * @param resource $file
     *
     * @return array
     */
    public function read($file): array
    {
        $imagick = new \Imagick();
        $imagick->readImageFile($file);

        $result = [];
        $result['width'] = $imagick->getImageWidth();
        $result['height'] = $imagick->getImageHeight();
        $resolution = $imagick->getImageResolution();
        $units = $imagick->getImageUnits();

        $result['alpha'] = false;
        if ($imagick->getImageAlphaChannel()) {
            $result['alpha'] = true;
        }

        if (array_key_exists('x', $resolution) && $resolution['x'] > 0) {
            if (\Imagick::RESOLUTION_PIXELSPERCENTIMETER === $units) {
                $result['resolution'] = sprintf('%s pixel/cm', round($resolution['x'], 2));
            } elseif (\Imagick::RESOLUTION_PIXELSPERINCH === $units) {
                $result['resolution'] = sprintf('%s pixel/in', round($resolution['x'], 2));
            } else {
                $result['resolution'] = sprintf('%s pixel', round($resolution['x'], 2));
            }
        }

        return $result;
    }
}
