<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Template;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

interface TemplateElementBuilderInterface
{
    public function supported(string $type): bool;

    public function build(
        Template $template,
        Position $position,
        Size $size,
        array $properties
    ): TemplateElementInterface;
}
