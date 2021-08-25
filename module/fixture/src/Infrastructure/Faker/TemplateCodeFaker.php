<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ergonode\Designer\Domain\ValueObject\TemplateCode;

class TemplateCodeFaker extends BaseProvider
{
    public function templateCode(string $code): TemplateCode
    {
         return new TemplateCode($code);
    }
}
