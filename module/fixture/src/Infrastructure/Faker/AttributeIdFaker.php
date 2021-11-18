<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

class AttributeIdFaker extends BaseProvider
{
    private const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @throws \Exception
     */
    public function attributeId(?string $code = null): AttributeId
    {
        if ($code) {
            return new AttributeId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return AttributeId::generate();
    }
}
