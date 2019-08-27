<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\ProfileQueryInterface;
use Ergonode\Core\Application\Controller\AbstractApiController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class ProfileController extends AbstractApiController
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
     * @Route("profile", methods={"GET"})
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Response(
     *     response=200,
     *     description="Returns information about current logged user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Response(
     *     response=422,
     *     description="Unprocessable entity"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getProfile(Request $request): Response
    {
        if ($this->getUser()) {
            /** @var User $profile */
            $profile = $this->query->getProfile($this->getUser()->getId());

            return $this->createRestResponse($profile);
        }

        throw new UnprocessableEntityHttpException();
    }
}
