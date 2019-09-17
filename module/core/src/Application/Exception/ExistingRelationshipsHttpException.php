<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Exception;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 */
class ExistingRelationshipsHttpException extends HttpException
{
    /**
     * @param RelationshipCollection $relationships
     */
    public function __construct(RelationshipCollection $relationships)
    {
        $message = sprintf(
            'Element cannot be removed because it has active relationships with %s',
            implode(', ', $relationships->getKeys())
        );

        parent::__construct(Response::HTTP_CONFLICT, $message);
    }
}
