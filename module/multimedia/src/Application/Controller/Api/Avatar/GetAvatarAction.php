<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Avatar;

use Ergonode\Api\Application\Response\FileContentResponse;
use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\Multimedia\Infrastructure\Storage\ResourceStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_avatar_read",
 *     path="/avatar/{avatar}",
 *     methods={"GET"},
 * )
 */
class GetAvatarAction
{
    /**
     * @var ResourceStorageInterface
     */
    private ResourceStorageInterface $avatarStorage;

    /**
     * @param ResourceStorageInterface $avatarStorage
     */
    public function __construct(ResourceStorageInterface $avatarStorage)
    {
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * @SWG\Tag(name="Avatar")
     * @SWG\Parameter(
     *     name="avatar",
     *     in="path",
     *     type="string",
     *     description="Avatar id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns avatar file",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Avatar $avatar
     *
     * @ParamConverter(class="Ergonode\Multimedia\Domain\Entity\Avatar")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Avatar $avatar): Response
    {
        $content = $this->avatarStorage->read($avatar->getFileName());

        return new FileContentResponse($content, $avatar);
    }
}
