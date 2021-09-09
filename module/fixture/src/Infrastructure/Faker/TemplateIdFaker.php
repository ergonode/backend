<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Faker\Provider\Base as BaseProvider;

class TemplateIdFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function templateId(string $uuid = null): TemplateId
    {
        if ($uuid) {
            return new TemplateId($uuid);
        }

        return TemplateId::generate();
    }
}
