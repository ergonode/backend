<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine;

use Doctrine\Persistence\ObjectRepository;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

interface RefreshTokenRepositoryInterface extends ObjectRepository
{
    public function insert(RefreshToken $token);
    public function delete(RefreshToken $token);
}
