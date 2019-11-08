<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Comment\Domain\Entity\CommentId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class CommentIdFaker extends BaseProvider
{
    /**
     * @return CommentId
     *
     * @throws \Exception
     */
    public function commentId(): CommentId
    {
        return CommentId::generate();
    }
}
