<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Action;

use Symfony\Component\HttpFoundation\Request;

class GetAction extends AbstractAction
{
    public function getMethod(): string
    {
        return Request::METHOD_GET;
    }

}