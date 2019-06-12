<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Designer\Domain\Entity\TemplateElementId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class TemplateElementIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function templateElementId($code = null): TemplateElementId
    {

        if ($code) {
            return new TemplateElementId((string) $code);
        }

        return TemplateElementId::generate();
    }
}
