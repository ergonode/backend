<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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
    private NotificationQueryInterface $query;

    private AuthenticatedUserProviderInterface $userProvider;

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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns notification information for current user",
     * )
     *
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
