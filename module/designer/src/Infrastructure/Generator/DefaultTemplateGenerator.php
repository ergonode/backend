<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Generator;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;

/**
 */
class DefaultTemplateGenerator implements TemplateGeneratorInterface
{
    public const CODE = 'DEFAULT';

    /**
     * @param TemplateId      $id
     * @param TemplateGroupId $groupId
     *
     * @return Template
     */
    public function getTemplate(TemplateId $id, TemplateGroupId $groupId): Template
    {
        return new Template($id, $groupId, 'Default');
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return self::CODE;
    }
}
