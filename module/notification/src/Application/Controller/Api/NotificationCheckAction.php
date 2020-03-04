<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Application\Controller\Api;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/notifications/check", methods={"GET"})
 */
class NotificationCheckAction
{
    /**
     * @var NotificationQueryInterface
     */
    private NotificationQueryInterface $query;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @param NotificationQueryInterface         $query
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(NotificationQueryInterface $query, AuthenticatedUserProviderInterface $userProvider)
    {
        $this->query = $query;
        $this->userProvider = $userProvider;
    }

    /**
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns notification information for current user",
     * )
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(): Response
    {
        $user = $this->userProvider->provide();
        $result = $this->query->check($user->getId());

        return new SuccessResponse($result);
    }
}
