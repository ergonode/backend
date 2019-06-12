<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Infrastructure\Generator;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;

/**
 */
interface TemplateGeneratorInterface
{
    public const DEFAULT = 'DEFAULT';

    /**
     * @param TemplateId      $id
     * @param TemplateGroupId $groupId
     *
     * @return Template
     */
    public function getTemplate(TemplateId $id, TemplateGroupId $groupId): Template;

    /**
     * @return string
     */
    public function getCode(): string;
}
