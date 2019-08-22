<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 */
class AbstractResponse extends Response
{
    /**
     * @param mixed $content
     *
     * @return AbstractResponse
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }
}
