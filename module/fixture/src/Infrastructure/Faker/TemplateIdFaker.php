<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
    public function templateId(): TemplateId
    {
        return TemplateId::generate();
    }
}
