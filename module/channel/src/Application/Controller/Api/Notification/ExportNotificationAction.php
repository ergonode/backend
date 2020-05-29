<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Controller\Api\Notification;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;

/**
 * @Route("/profile/exports", methods={"GET"})
 */
class ExportNotificationAction
{
    /**
     * @var ExportQueryInterface
     */
    private ExportQueryInterface $query;

    /**
     * @param ExportQueryInterface $query
     */
    public function __construct(ExportQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("CHANNEL_READ")
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns export information",
     * )
     *
     * @param Language $language
     *
     * @return Response
     */
    public function __invoke(Language $language): Response
    {
        $data = $this->query->getProfileInfo($language);

        return new SuccessResponse($data);
    }
}
