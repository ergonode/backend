<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api\Notification;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\BatchAction\Domain\Query\BatchActionQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;

/**
 * @Route("/profile/batch-action", methods={"GET"})
 */
class BatchActionNotificationAction
{
    private BatchActionQueryInterface $query;

    public function __construct(BatchActionQueryInterface $query)
    {
        $this->query = $query;
    }
    /**
     * @IsGranted("CHANNEL_GET_NOTIFICATION")
     *
     * @SWG\Tag(name="Profile")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns export information",
     * )
     */
    public function __invoke(Language $language): Response
    {
        $data = $this->query->getProfileInfo();

        return new SuccessResponse($data);
    }
}
