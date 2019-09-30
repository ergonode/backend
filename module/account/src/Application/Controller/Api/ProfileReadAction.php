<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\ProfileQueryInterface;
use Ergonode\Api\Application\Response\SuccessResponse;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("profile", methods={"GET"})
 */
class ProfileReadAction
{
    /**
     * @var ProfileQueryInterface
     */
    private $query;

    /**
     * @param ProfileQueryInterface $query
     */
    public function __construct(ProfileQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Profile")
     * @SWG\Response(
     *     response=200,
     *     description="Returns information about current logged user"
     * )
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        /** @var User $profile */
        $profile = $this->query->getProfile($this->getUser()->getId());

        return new SuccessResponse($profile);
    }
}
