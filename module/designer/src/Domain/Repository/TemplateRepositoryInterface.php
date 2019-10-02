<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Repository;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateId;

/**
 */
interface TemplateRepositoryInterface
{
    /**
     * @param TemplateId $id
     *
     * @return Template|null
     */
    public function load(TemplateId $id): ?Template;

    /**
     * @param Template $template
     */
    public function save(Template $template): void;

    /**
     * @param Template $template
     */
    public function delete(Template $template): void;
}
