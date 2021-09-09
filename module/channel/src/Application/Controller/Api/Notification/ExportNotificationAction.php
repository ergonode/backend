<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Controller\Api\Notification;

use Ergonode\Core\Domain\ValueObject\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;

/**
 * @Route("/profile/exports", methods={"GET"})
 */
class ExportNotificationAction
{
    private ExportQueryInterface $query;

    public function __construct(ExportQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_CHANNEL_GET_NOTIFICATION")
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
    public function __invoke(Language $language): array
    {
        return $this->query->getProfileInfo($language);
    }
}
