<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\Response;

class AbstractResponse extends Response
{
    /**
     * {@inheritDoc}
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }
}
