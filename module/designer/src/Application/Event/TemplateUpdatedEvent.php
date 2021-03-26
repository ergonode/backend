<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Event;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class TemplateUpdatedEvent implements ApplicationEventInterface
{
    private Template $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }
}
