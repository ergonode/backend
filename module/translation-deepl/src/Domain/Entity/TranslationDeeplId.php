<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Domain\Entity;


use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

class TranslationDeeplId extends AbstractId
{
    public const NAMESPACE = 'f39d019e-92f0-47e8-b5ee-81155e7ddfc2'; //nie wiem jaki powineim byc

    /**
     * @param string $value
     *
     * @return TranslationDeeplId
     *
     * @throws \Exception
     */
    public static function fromKey(string $value): TranslationDeeplId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
