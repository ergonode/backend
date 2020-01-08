<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class FileFaker extends BaseProvider
{
    /**
     * @param string $name
     *
     * @return File
     */
    public function multimediaFile(string $name): File
    {
        $path = sprintf('%s/../../Resources/image/%s', __DIR__, $name);
        $tmp = sprintf('%s/../../Resources/image/tmp/%s', __DIR__, $name);
        copy($path, $tmp);

        return new File($tmp);
    }
}
