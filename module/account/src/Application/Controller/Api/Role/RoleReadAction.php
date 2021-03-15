<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Role;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Api\Application\Response\SuccessResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_account_role_read",
 *     path="/roles/{role}",
 *     methods={"GET"},
 *     requirements={"role"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class RoleReadAction
{
    /**
     * @IsGranted("ACCOUNT_GET_ROLE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="role",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="Role ID"
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
     *     description="Returns role data"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     */
    public function __invoke(Role $role): Response
    {
        return new SuccessResponse($role);
    }
}
