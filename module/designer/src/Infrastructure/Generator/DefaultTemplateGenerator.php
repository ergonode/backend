<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Generator;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class DefaultTemplateGenerator implements TemplateGeneratorInterface
{
    public const CODE = 'DEFAULT';

    /**
     * @throws \Exception
     */
    public function getTemplate(TemplateId $id, TemplateGroupId $groupId): Template
    {
        return new Template($id, $groupId, 'Default');
    }

    public function getCode(): string
    {
        return self::CODE;
    }
}
