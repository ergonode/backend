<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

use Ergonode\Core\Application\Exception\DenoralizationException;
use Ergonode\Core\Application\Exception\NormalizationException;

interface NormalizerInterface
{
    /**
     * Normalizes data i.e. object(s) into normal(scalar) form.
     *
     * @throws NormalizationException
     *
     * @param mixed $data
     * @return mixed
     */
    public function normalize($data);

    /**
     * Denormalizes normal(scalar) form data into i.e. object(s).
     *
     * @throws DenoralizationException
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function denormalize($data, string $type);
}
