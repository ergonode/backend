<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Api\Application\Response\FileContentResponse;
use League\Flysystem\FilesystemInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route(
 *     name="ergonode_avatar_read",
 *     path="/accounts/{user}/avatar",
 *     methods={"GET"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 * )
 */
class AvatarReadAction
{
    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $avatarStorage;

    /**
     * @param FilesystemInterface $avatarStorage
     */
    public function __construct(FilesystemInterface $avatarStorage)
    {
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * @IsGranted("USER_UPDATE")
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
     *     default="en",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(User $user, Request $request): Response
    {
        $filename = sprintf('%s.%s', $user->getId()->getValue(), 'png');

        return new FileContentResponse($filename, $this->avatarStorage);
    }
}
