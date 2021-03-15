<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Api\Application\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_account_user_read",
 *     path="/accounts/{user}",
 *     methods={"GET"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class UserReadAction
{
    private AccountQueryInterface $query;

    public function __construct(AccountQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("ACCOUNT_GET")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User ID"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns user data"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(User $user): Response
    {
        $user = $this->query->getUser($user->getId());
        if (empty($user)) {
            throw new NotFoundHttpException('User data not found');
        }

        return new SuccessResponse($user);
    }
}
