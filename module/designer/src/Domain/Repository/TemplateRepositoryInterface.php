<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Repository;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

interface TemplateRepositoryInterface
{
    public function load(TemplateId $id): ?Template;

    public function save(Template $template): void;

    public function delete(Template $template): void;
}
