<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;

/**
 * Class MultimediaFileProviderInterface
 */
interface MultimediaFileProviderInterface
{
    /**
     * @param Multimedia $multimedia
     *
     * @return string
     */
    public function getFile(Multimedia $multimedia): string;
}
